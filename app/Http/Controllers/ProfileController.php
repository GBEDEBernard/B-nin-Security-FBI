<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $guard = $this->getGuard();
        return view('admin.profile.index', compact('user', 'guard'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $guard = $this->getGuard();

        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique($user->getTable())->ignore($user->id)],
            'telephone' => 'nullable|string|max:20',
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

        if ($user instanceof \App\Models\Employe) {
            $user->update($validated);
        } elseif ($user instanceof \App\Models\Client) {
            $user->update($validated);
        } else {
            $data = [];
            if (isset($validated['name'])) $data['name'] = $validated['name'];
            if (isset($validated['email'])) $data['email'] = $validated['email'];
            if (isset($validated['telephone'])) $data['telephone'] = $validated['telephone'];
            $user->update($data);
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);
        return view('admin.profile.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->update(['lu_le' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->whereNull('lu_le')->update(['lu_le' => now()]);
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function unreadCount()
    {
        $count = Auth::user()->notifications()->whereNull('lu_le')->count();
        $notifications = Auth::user()->notifications()->whereNull('lu_le')->take(5)->get()->map(function ($n) {
            $data = is_string($n->donnees) ? json_decode($n->donnees, true) : ($n->donnees ?? []);
            return [
                'id' => $n->id,
                'type' => class_basename($n->type ?? 'Notification'),
                'message' => $data['message'] ?? $data['titre'] ?? 'Nouvelle notification',
                'icon' => $data['icon'] ?? 'bell',
                'color' => $data['color'] ?? 'primary',
                'time' => $n->created_at->diffForHumans(),
            ];
        });
        return response()->json(['count' => $count, 'notifications' => $notifications]);
    }

    private function getGuard(): string
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user instanceof \App\Models\Employe) {
                if ($user->estAgent()) {
                    return 'admin.agent';
                }
                return 'admin.entreprise';
            }
            return 'admin.superadmin';
        }
        if (Auth::guard('client')->check()) {
            return 'admin.client';
        }
        return 'admin.superadmin';
    }
}
