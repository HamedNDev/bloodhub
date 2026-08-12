<?php

namespace App\Http\Controllers;

use App\Models\BanqueDeSang;
use App\Models\Demande;
use App\Models\Hopital;
use App\Models\Stock;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin'          => $this->admin(),
            'hopital'        => $this->hopital(),
            'banque_de_sang' => $this->banque(),
            'donneur'        => $this->donneur(),
            default          => $this->patient(),
        };
    }

    private function patient()
    {
        $user = auth()->user();
        $stats = [
            'total'     => Demande::where('user_id', $user->id)->count(),
            'en_cours'  => Demande::where('user_id', $user->id)->where('statut', 'en_cours')->count(),
            'satisfaite'=> Demande::where('user_id', $user->id)->where('statut', 'satisfaite')->count(),
        ];
        $recentes = Demande::where('user_id', $user->id)->latest()->take(5)->get();
        return view('dashboard.patient', compact('stats', 'recentes'));
    }

    private function donneur()
    {
        $user = auth()->user();
        $donneur = $user->donneur;
        return view('dashboard.donneur', compact('donneur'));
    }

    private function hopital()
    {
        $user = auth()->user();
        $hopital = $user->hopital;
        $stats = [
            'total'      => Demande::where('hopital_id', $hopital?->id)->count(),
            'en_attente' => Demande::where('hopital_id', $hopital?->id)->where('statut', 'en_attente')->count(),
            'en_cours'   => Demande::where('hopital_id', $hopital?->id)->where('statut', 'en_cours')->count(),
            'satisfaite' => Demande::where('hopital_id', $hopital?->id)->where('statut', 'satisfaite')->count(),
        ];
        $recentes = Demande::where('hopital_id', $hopital?->id)->latest()->take(5)->get();
        return view('dashboard.hopital', compact('stats', 'recentes', 'hopital'));
    }

    private function banque()
    {
        $user   = auth()->user();
        $banque = $user->banqueDeSang;
        $stocks = Stock::where('banque_de_sang_id', $banque?->id)->get()->keyBy('groupe_sanguin');
        $alertes = Stock::where('banque_de_sang_id', $banque?->id)
            ->whereRaw('quantite <= seuil_alerte')->count();
        $demandes = Demande::where('banque_de_sang_id', $banque?->id)
            ->whereIn('statut', ['en_attente', 'en_cours'])->count();
        return view('dashboard.banque', compact('stocks', 'alertes', 'demandes', 'banque'));
    }

    private function admin()
    {
        $stats = [
            'utilisateurs'  => User::count(),
            'hopitaux'      => Hopital::count(),
            'banques'       => BanqueDeSang::count(),
            'donneurs'      => User::where('role', 'donneur')->count(),
            'patients'      => User::where('role', 'patient')->count(),
            'demandes'      => Demande::count(),
            'en_attente'    => Demande::where('statut', 'en_attente')->count(),
            'en_cours'      => Demande::where('statut', 'en_cours')->count(),
            'satisfaites'   => Demande::where('statut', 'satisfaite')->count(),
            'annulees'      => Demande::where('statut', 'annulee')->count(),
            'paiements'     => \App\Models\Paiement::count(),
            'paiements_confirmes' => \App\Models\Paiement::where('statut', 'confirme')->count(),
            'stocks_critiques' => Stock::whereRaw('quantite <= seuil_alerte')->count(),
            'cagnottes'     => \App\Models\Cagnotte::count(),
            'cagnottes_actives' => \App\Models\Cagnotte::where('statut', 'active')->count(),
        ];

        $recentes = Demande::with(['user', 'hopital', 'banqueDeSang'])->latest()->take(8)->get();

        // Derniers utilisateurs inscrits
        $nouveauxUsers = User::latest()->take(5)->get();

        // Demandes urgentes non traitées
        $urgentes = Demande::with(['user', 'hopital'])
            ->whereIn('urgence', ['urgente', 'critique'])
            ->where('statut', 'en_attente')
            ->latest()->take(5)->get();

        // Stocks critiques
        $stocksCritiques = Stock::with('banqueDeSang')
            ->whereRaw('quantite <= seuil_alerte')
            ->orderBy('quantite')
            ->take(6)->get();

        // Derniers paiements
        $derniersPaiements = \App\Models\Paiement::with(['user', 'demande'])
            ->latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'stats', 'recentes', 'nouveauxUsers',
            'urgentes', 'stocksCritiques', 'derniersPaiements'
        ));
    }
}

