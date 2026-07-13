<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private function getCurrentUser()
    {
        if (Auth::guard('employe')->check()) {
            return Auth::guard('employe')->user();
        }
        if (Auth::guard('client')->check()) {
            return Auth::guard('client')->user();
        }
        return Auth::guard('web')->user();
    }

    public function show()
    {
        $user = $this->getCurrentUser();
        $guard = $this->getGuard();
        return view('admin.profile.index', compact('user', 'guard'));
    }

    public function update(Request $request)
    {
        $user = $this->getCurrentUser();
        $guard = $this->getGuard();

        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique($user->getTable())->ignore($user->id)],
            'telephone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if ($user instanceof \App\Models\Employe) {
            $rules['prenoms'] = 'sometimes|string|max:255';
            $rules['nom'] = 'sometimes|string|max:255';
            $rules['adresse'] = 'nullable|string|max:500';
            $rules['telephone_urgence'] = 'nullable|string|max:20';
        }

        if ($user instanceof \App\Models\Client) {
            $rules['nom'] = 'sometimes|string|max:255';
            $rules['prenoms'] = 'sometimes|string|max:255';
            $rules['adresse'] = 'nullable|string|max:500';
            $rules['ville'] = 'nullable|string|max:100';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos/profiles', 'public');
        }

        if ($user instanceof \App\Models\Employe || $user instanceof \App\Models\Client) {
            $user->update($validated);
        } else {
            $data = [];
            if (isset($validated['name'])) $data['name'] = $validated['name'];
            if (isset($validated['email'])) $data['email'] = $validated['email'];
            if (isset($validated['telephone'])) $data['telephone'] = $validated['telephone'];
            if (isset($validated['photo'])) $data['photo'] = $validated['photo'];
            $user->update($data);
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $user = $this->getCurrentUser();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }

    public function notifications()
    {
        $user = $this->getCurrentUser();
        $notifications = $user->notifications()->paginate(20);
        return view('admin.profile.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $user = $this->getCurrentUser();
        $notif = $user->notifications()->findOrFail($id);
        $notif->update(['lu_le' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = $this->getCurrentUser();
        $user->notifications()->whereNull('lu_le')->update(['lu_le' => now()]);
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function unreadCount()
    {
        $user = $this->getCurrentUser();
        $count = $user->notifications()->whereNull('lu_le')->count();
        $notifications = $user->notifications()->whereNull('lu_le')->take(5)->get()->map(function ($n) {
            $data = is_string($n->donnees) ? json_decode($n->donnees, true) : ($n->donnees ?? []);
            return [
                'id' => $n->id,
                'type' => class_basename($n->type ?? 'Notification'),
                'message' => $data['message'] ?? $data['titre'] ?? 'Nouvelle notification',
                'icon' => $data['icon'] ?? 'bell',
                'color' => $data['color'] ?? 'primary',
                'time' => $n->created_at->diffForHumans(),
                'url' => $data['url'] ?? null,
            ];
        });
        return response()->json(['count' => $count, 'notifications' => $notifications]);
    }

    private function getGuard(): string
    {
        if (Auth::guard('employe')->check()) {
            $user = Auth::guard('employe')->user();
            return $user->estAgent() ? 'admin.agent' : 'admin.entreprise';
        }
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user instanceof \App\Models\Employe) {
                return $user->estAgent() ? 'admin.agent' : 'admin.entreprise';
            }
            return 'admin.superadmin';
        }
        if (Auth::guard('client')->check()) {
            return 'admin.client';
        }
        return 'admin.superadmin';
    }
}
