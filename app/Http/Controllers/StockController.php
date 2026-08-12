<?php

namespace App\Http\Controllers;

use App\Models\BanqueDeSang;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stocks = Stock::with('banqueDeSang')->get()->groupBy('banque_de_sang_id');
            $banques = BanqueDeSang::all();
            return view('stocks.admin', compact('stocks', 'banques'));
        }

        $banque = $user->banqueDeSang;
        if (!$banque) abort(403);

        $stocks  = Stock::where('banque_de_sang_id', $banque->id)->get()->keyBy('groupe_sanguin');
        $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        return view('stocks.index', compact('stocks', 'groupes', 'banque'));
    }

    public function update(Request $request, string $groupe)
    {
        $request->validate([
            'quantite'     => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:0',
        ]);

        $banque = auth()->user()->banqueDeSang;
        if (!$banque) abort(403);

        Stock::updateOrCreate(
            ['banque_de_sang_id' => $banque->id, 'groupe_sanguin' => $groupe],
            [
                'quantite'            => $request->quantite,
                'seuil_alerte'        => $request->seuil_alerte,
                'derniere_mise_a_jour' => now(),
            ]
        );

        return back()->with('success', "Stock $groupe mis à jour.");
    }

    public function public()
    {
        $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $banques = BanqueDeSang::where('actif', true)->with('stocks')->get();
        return view('stocks.public', compact('banques', 'groupes'));
    }
}

