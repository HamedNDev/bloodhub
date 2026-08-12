<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DonneurAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'donneur')->with('donneur');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('telephone', 'like', "%{$request->search}%");
            });
        }

        if ($request->groupe) {
            $query->where('groupe_sanguin', $request->groupe);
        }

        if ($request->disponible !== null && $request->disponible !== '') {
            $query->whereHas('donneur', fn($q) => $q->where('disponible', $request->disponible));
        }

        $donneurs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.donneurs.index', compact('donneurs'));
    }

    public function toggleActif(User $user)
    {
        if ($user->role !== 'donneur') abort(403);

        // On utilise le champ notifications_actives du donneur comme "actif"
        if ($user->donneur) {
            $user->donneur->update([
                'notifications_actives' => !$user->donneur->notifications_actives,
            ]);
        }

        return back()->with('success', 'Statut du donneur mis à jour.');
    }

    public function toggleDisponible(User $user)
    {
        if ($user->role !== 'donneur' || !$user->donneur) abort(403);

        $user->donneur->update(['disponible' => !$user->donneur->disponible]);

        return back()->with('success', 'Disponibilité mise à jour.');
    }
}

