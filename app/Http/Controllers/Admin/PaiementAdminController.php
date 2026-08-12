<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::with(['user', 'demande']);

        if ($request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
                ->orWhere('reference', 'like', "%{$request->search}%");
        }

        $paiements = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'     => Paiement::count(),
            'confirmes' => Paiement::where('statut', 'confirme')->count(),
            'attente'   => Paiement::where('statut', 'en_attente')->count(),
            'echoues'   => Paiement::where('statut', 'echoue')->count(),
            'montant'   => Paiement::where('statut', 'confirme')->sum('montant'),
        ];

        return view('admin.paiements.index', compact('paiements', 'stats'));
    }
}

