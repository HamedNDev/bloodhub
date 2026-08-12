<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Notification;
use Illuminate\Http\Request;

class DonneurController extends Controller
{
    public function dashboard()
    {
        $user    = auth()->user();
        $donneur = $user->donneur;
        return view('donneur.dashboard', compact('donneur'));
    }

    public function toggleDisponibilite()
    {
        $donneur = auth()->user()->donneur;
        $donneur->update(['disponible' => !$donneur->disponible]);
        $statut = $donneur->disponible ? 'disponible' : 'indisponible';
        return back()->with('success', "Vous êtes maintenant $statut.");
    }

    public function alertes()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->where('type', 'alerte_don')
            ->with('demande')
            ->latest()
            ->paginate(15);
        return view('donneur.alertes', compact('notifications'));
    }

    public function marquerAlerteLue(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) abort(403);
        $notification->update(['lue' => true]);
        return back();
    }

    public function historique()
    {
        $donneur = auth()->user()->donneur;
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()->paginate(20);
        return view('donneur.historique', compact('donneur', 'notifications'));
    }

    public function enregistrerDon(Request $request)
    {
        $donneur = auth()->user()->donneur;
        $donneur->update([
            'dernier_don' => now(),
            'nombre_dons' => $donneur->nombre_dons + 1,
        ]);
        return back()->with('success', 'Don enregistré. Merci pour votre générosité !');
    }
}

