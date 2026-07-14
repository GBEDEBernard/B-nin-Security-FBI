<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\User;
use App\Models\Employe;
use App\Models\Client;
use App\Models\ContratPrestation;
use App\Models\PropositionContrat;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->input('q');

        if (!$q || strlen(trim($q)) < 2) {
            if ($request->ajax()) {
                return response()->json([]);
            }
            return redirect()->back()->with('error', 'Veuillez saisir au moins 2 caractères.');
        }

        $query = trim($q);
        $like = '%' . $query . '%';

        $entreprises = Entreprise::where('nom_entreprise', 'like', $like)
            ->orWhere('nom_commercial', 'like', $like)
            ->orWhere('email', 'like', $like)
            ->orWhere('telephone', 'like', $like)
            ->orWhere('ville', 'like', $like)
            ->orWhere('formule', 'like', $like)
            ->orderBy('nom_entreprise')
            ->limit(5)
            ->get()
            ->map(fn($e) => [
                'type' => 'Entreprise',
                'url' => route('admin.superadmin.entreprises.show', $e->id),
                'titre' => $e->nom_entreprise,
                'sous_titre' => $e->email ?: 'Email non renseigné',
                'icone' => 'building',
                'badge' => $e->est_active ? ['text' => 'Actif', 'class' => 'bg-success'] : ['text' => 'Inactif', 'class' => 'bg-secondary'],
            ]);

        $users = User::where('name', 'like', $like)
            ->orWhere('email', 'like', $like)
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->map(fn($u) => [
                'type' => 'Utilisateur',
                'url' => route('admin.superadmin.utilisateurs.show', $u->id),
                'titre' => $u->name,
                'sous_titre' => $u->email,
                'icone' => 'person',
                'badge' => null,
            ]);

        $employes = Employe::where('nom', 'like', $like)
            ->orWhere('prenoms', 'like', $like)
            ->orWhere('email', 'like', $like)
            ->orWhere('telephone', 'like', $like)
            ->orWhere('matricule', 'like', $like)
            ->orWhere('poste', 'like', $like)
            ->orderBy('nom')
            ->limit(5)
            ->get()
            ->map(fn($e) => [
                'type' => 'Employé',
                'url' => route('admin.superadmin.entreprises.show', $e->entreprise_id),
                'titre' => $e->prenoms . ' ' . $e->nom,
                'sous_titre' => $e->poste ?: 'Poste non renseigné',
                'icone' => 'person-badge',
                'badge' => match($e->statut) {
                    'en_poste' => ['text' => 'En poste', 'class' => 'bg-success'],
                    'conge' => ['text' => 'Congé', 'class' => 'bg-warning'],
                    default => ['text' => $e->statut, 'class' => 'bg-secondary'],
                },
            ]);

        $clients = Client::where('nom', 'like', $like)
            ->orWhere('prenoms', 'like', $like)
            ->orWhere('raison_sociale', 'like', $like)
            ->orWhere('email', 'like', $like)
            ->orWhere('telephone', 'like', $like)
            ->orderBy('nom')
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'type' => 'Client',
                'url' => '#',
                'titre' => $c->raison_sociale ?: ($c->prenoms . ' ' . $c->nom),
                'sous_titre' => $c->email ?: 'Email non renseigné',
                'icone' => 'briefcase',
                'badge' => null,
            ]);

        $contrats = ContratPrestation::with('entreprise')
            ->where('numero_contrat', 'like', $like)
            ->orWhere('intitule', 'like', $like)
            ->orWhere('statut', 'like', $like)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'type' => 'Contrat',
                'url' => route('admin.superadmin.contrats.show', $c->id),
                'titre' => $c->intitule ?: 'Sans titre',
                'sous_titre' => $c->numero_contrat ?: 'N° non défini',
                'icone' => 'file-text',
                'badge' => match($c->statut) {
                    'en_cours' => ['text' => 'En cours', 'class' => 'bg-success'],
                    'brouillon' => ['text' => 'Brouillon', 'class' => 'bg-secondary'],
                    'suspendu' => ['text' => 'Suspendu', 'class' => 'bg-warning'],
                    default => ['text' => $c->statut, 'class' => 'bg-danger'],
                },
            ]);

        $propositions = PropositionContrat::where('nom_entreprise', 'like', $like)
            ->orWhere('email', 'like', $like)
            ->orWhere('telephone', 'like', $like)
            ->orWhere('statut', 'like', $like)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'type' => 'Proposition',
                'url' => route('admin.superadmin.propositions.show', $p->id),
                'titre' => $p->nom_entreprise,
                'sous_titre' => $p->email ?: 'Email non renseigné',
                'icone' => 'file-earmark',
                'badge' => null,
            ]);

        $results = collect()
            ->concat($entreprises)
            ->concat($users)
            ->concat($employes)
            ->concat($clients)
            ->concat($contrats)
            ->concat($propositions);

        if ($request->ajax()) {
            return response()->json(['results' => $results, 'total' => $results->count()]);
        }

        $total = $results->count();
        return view('layouts.search-results', compact('q', 'query', 'results', 'total'));
    }
}
