<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ApkVersion;
use App\Models\ConfigurationMobile;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApkController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);

        $this->middleware('permission:manage_tenant_settings')->only([
            'index', 'show', 'create', 'store', 'activate', 'deactivate',
            'destroy', 'qrcode', 'configurations', 'updateConfigurations', 'download',
        ]);
    }

    public function index()
    {
        $versions = ApkVersion::with('publieur')
            ->orderBy('version_code', 'desc')
            ->paginate(10);

        $versionActive = ApkVersion::where('est_active', true)->first();

        $stats = [
            'total_telechargements' => ApkVersion::sum('telechargements'),
            'version_active' => $versionActive?->version ?? 'Aucune',
            'total_versions' => ApkVersion::count(),
            'derniere_stable' => ApkVersion::where('type', 'stable')->orderBy('version_code', 'desc')->first(),
        ];

        return view('admin.superadmin.apk.index', compact('versions', 'stats', 'versionActive'));
    }

    public function show(ApkVersion $apkVersion)
    {
        $apkVersion->load('publieur');
        return view('admin.superadmin.apk.show', compact('apkVersion'));
    }

    public function create()
    {
        $dernierCode = ApkVersion::max('version_code') ?? 0;
        $prochainCode = $dernierCode + 1;
        return view('admin.superadmin.apk.create', compact('prochainCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:20|unique:apk_versions,version',
            'version_code' => 'required|integer|unique:apk_versions,version_code',
            'type' => 'required|in:alpha,beta,stable',
            'fichier' => 'required|file|mimes:apk|max:102400',
            'notes' => 'nullable|string|max:5000',
            'changelog' => 'nullable|array',
            'changelog.*' => 'string|max:500',
            'publier' => 'boolean',
            'est_obligatoire' => 'boolean',
        ]);

        $fichier = $request->file('fichier');
        $nomFichier = 'v' . Str::slug($validated['version']) . '_' . $validated['version_code'] . '.apk';
        $chemin = $fichier->storeAs('', $nomFichier, 'apk');

        $version = ApkVersion::create([
            'version' => $validated['version'],
            'version_code' => $validated['version_code'],
            'type' => $validated['type'],
            'fichier_path' => $chemin,
            'taille' => $fichier->getSize(),
            'checksum' => hash_file('sha256', $fichier->getRealPath()),
            'notes' => $validated['notes'] ?? null,
            'changelog' => $validated['changelog'] ?? null,
            'est_obligatoire' => $request->boolean('est_obligatoire'),
            'est_active' => $request->boolean('publier'),
            'date_publication' => $request->boolean('publier') ? now() : null,
            'publie_par' => auth()->id(),
        ]);

        if ($request->boolean('publier')) {
            $version->activate();
        }

        return redirect()->route('admin.superadmin.apk.index')
            ->with('success', "Version {$version->version} publiée avec succès.");
    }

    public function activate(ApkVersion $apkVersion)
    {
        $apkVersion->activate();
        return redirect()->route('admin.superadmin.apk.index')
            ->with('success', "Version {$apkVersion->version} activée.");
    }

    public function deactivate(ApkVersion $apkVersion)
    {
        $apkVersion->deactivate();
        return redirect()->route('admin.superadmin.apk.index')
            ->with('success', "Version {$apkVersion->version} désactivée.");
    }

    public function destroy(ApkVersion $apkVersion)
    {
        if ($apkVersion->fichier_path) {
            Storage::disk('apk')->delete($apkVersion->fichier_path);
        }
        $apkVersion->delete();
        return redirect()->route('admin.superadmin.apk.index')
            ->with('success', "Version {$apkVersion->version} supprimée.");
    }

    public function download(ApkVersion $apkVersion)
    {
        if (!$apkVersion->fichier_path || !Storage::disk('apk')->exists($apkVersion->fichier_path)) {
            return back()->with('error', 'Fichier APK introuvable.');
        }

        $apkVersion->incrementTelechargements();

        return Storage::disk('apk')->download(
            $apkVersion->fichier_path,
            "app-{$apkVersion->version}.apk"
        );
    }

    public function qrcode(Request $request)
    {
        $request->validate(['url' => 'required|url']);

        $options = new QROptions;
        $options->outputType = QRCode::OUTPUT_IMAGE_PNG;
        $options->scale = 10;
        $options->imageBase64 = true;

        $qrcode = (new QRCode($options))->render($request->url);

        return response()->json(['qrcode' => $qrcode]);
    }

    public function configurations()
    {
        $cles = [
            'url_api' => ['default' => config('app.url') . '/api', 'description' => 'URL de base de l\'API'],
            'version_minimum' => ['default' => '1.0.0', 'description' => 'Version minimale requise'],
            'notification_active' => ['default' => true, 'description' => 'Activer les notifications push'],
            'maintenance_mode' => ['default' => false, 'description' => 'Mode maintenance'],
            'geolocalisation_active' => ['default' => true, 'description' => 'Activer la géolocalisation'],
            'message_maintenance' => ['default' => '', 'description' => 'Message affiché en mode maintenance'],
        ];

        $configurations = [];
        foreach ($cles as $cle => $infos) {
            $configurations[$cle] = ConfigurationMobile::getValeur($cle, $infos['default']);
        }

        return view('admin.superadmin.apk.configurations', compact('configurations', 'cles'));
    }

    public function updateConfigurations(Request $request)
    {
        $validated = $request->validate([
            'url_api' => 'required|url',
            'version_minimum' => 'required|string|max:20',
            'notification_active' => 'boolean',
            'maintenance_mode' => 'boolean',
            'geolocalisation_active' => 'boolean',
            'message_maintenance' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $cle => $valeur) {
            if (in_array($cle, ['notification_active', 'maintenance_mode', 'geolocalisation_active'])) {
                $valeur = (bool) $valeur;
            }
            ConfigurationMobile::setValeur($cle, $valeur);
        }

        return redirect()->route('admin.superadmin.apk.configurations')
            ->with('success', 'Configurations mises à jour avec succès.');
    }
}
