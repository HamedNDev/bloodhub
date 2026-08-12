<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BanqueDeSang;
use App\Models\Hopital;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->latest()->paginate(20);

        $totalDonneurs   = User::where('role', 'donneur')->count();
        $totalHopitaux   = Hopital::count();
        $totalBanques    = BanqueDeSang::count();
        $totalPatients   = User::where('role', 'patient')->count();

        return view('admin.notifications.index', compact(
            'notifications', 'totalDonneurs', 'totalHopitaux', 'totalBanques', 'totalPatients'
        ));
    }

    public function envoyer(Request $request)
    {
        $request->validate([
            'titre'       => 'required|string|max:255',
            'message'     => 'required|string|max:1000',
            'destinataire'=> 'required|in:donneurs,hopitaux,banques,patients,tous',
            'type'        => 'required|in:info,alerte_don,urgence,stock_insuffisant',
        ]);

        $now = Carbon::now()->toDateTimeString();

        $query = User::query();
        match ($request->destinataire) {
            'donneurs'  => $query->where('role', 'donneur'),
            'hopitaux'  => $query->where('role', 'hopital'),
            'banques'   => $query->where('role', 'banque_de_sang'),
            'patients'  => $query->where('role', 'patient'),
            'tous'      => $query->whereIn('role', ['donneur', 'hopital', 'banque_de_sang', 'patient']),
        };

        $userIds = $query->pluck('id');

        $notifications = $userIds->map(fn($id) => [
            'user_id'    => $id,
            'titre'      => $request->titre,
            'message'    => $request->message,
            'type'       => $request->type,
            'demande_id' => null,
            'lue'        => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }

        return back()->with('success', count($notifications) . ' notification(s) envoyée(s) avec succès.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification supprimée.');
    }

    public function destroyAll()
    {
        Notification::truncate();
        return back()->with('success', 'Toutes les notifications ont été supprimées.');
    }
}

