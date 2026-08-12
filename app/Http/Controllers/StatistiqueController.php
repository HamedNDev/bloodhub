<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Demande;
use App\Models\Stock;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_donneurs' => User::where('role', 'donneur')->count(),
            'total_patients' => User::where('role', 'patient')->count(),
            'total_hopitaux' => User::where('role', 'hopital')->count(),
            'total_banques' => User::where('role', 'banque_de_sang')->count(),
            
            'total_demandes' => Demande::count(),
            'demandes_en_cours' => Demande::where('statut', 'en_cours')->count(),
            'demandes_satisfaites' => Demande::where('statut', 'satisfaite')->count(),
            'demandes_annulees' => Demande::where('statut', 'annulee')->count(),
        ];

        $demandes_recentes = Demande::with(['user', 'hopital', 'banqueDeSang'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.statistiques', compact('stats', 'demandes_recentes'));
    }
}
