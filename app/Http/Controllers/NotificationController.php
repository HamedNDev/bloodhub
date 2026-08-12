<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Debug simple
        try {
            $notifications = Notification::where('user_id', auth()->id())
                ->latest()->paginate(20);
            return view('notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            return 'Erreur: ' . $e->getMessage();
        }
    }

    public function marquerLue(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) abort(403);
        $notification->update(['lue' => true]);
        return back();
    }

    public function marquerToutesLues()
    {
        Notification::where('user_id', auth()->id())->update(['lue' => true]);
        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) abort(403);
        $notification->delete();
        return back()->with('success', 'Notification supprimée.');
    }
}

