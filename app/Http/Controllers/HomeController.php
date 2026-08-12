<?php

namespace App\Http\Controllers;

use App\Models\BanqueDeSang;
use App\Models\Demande;
use App\Models\Stock;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'donneurs' => User::where('role', 'donneur')->count(),
            'banques'  => BanqueDeSang::where('actif', true)->count(),
            'demandes' => Demande::where('statut', 'satisfaite')->count(),
        ];
        $banques = BanqueDeSang::where('actif', true)->with('stocks')->take(6)->get();
        $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return view('public.home', compact('stats', 'banques', 'groupes'));
    }
}

