<?php

namespace App\Http\Controllers;

use App\Models\BonPaiement;
use App\Models\Demande;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BonPaiementController extends Controller
{
    // Formulaire pour offrir un bon à un patient
    public function create(Request $request)
    {
        $demande = null;
        if ($request->demande_id) {
            $demande = Demande::findOrFail($request->demande_id);
        }
        $patients = User::where('role', 'patient')->get();
        return view('bons.create', compact('demande', 'patients'));
    }

    // Enregistrer le bon
    public function store(Request $request)
    {
        $request->validate([
            'beneficiaire_id' => 'required|exists:users,id',
            'demande_id'      => 'nullable|exists:demandes,id',
            'montant'         => 'required|numeric|min:500',
            'methode'         => 'required|in:orange_money,mtn_momo,autre',
            'message'         => 'nullable|string|max:255',
        ]);

        $bon = BonPaiement::create([
            'demande_id'       => $request->demande_id,
            'acheteur_id'      => auth()->id(),
            'beneficiaire_id'  => $request->beneficiaire_id,
            'montant'          => $request->montant,
            'code'             => strtoupper(Str::random(10)),
            'message'          => $request->message,
            'methode'          => $request->methode,
            'statut'           => 'actif',
            'expire_le'        => now()->addDays(30),
        ]);

        // Notifier le bénéficiaire
        Notification::create([
            'user_id'    => $request->beneficiaire_id,
            'titre'      => '🎁 Vous avez reçu un bon de paiement !',
            'message'    => auth()->user()->name . " vous a offert un bon de paiement de {$bon->montant} FCFA. Code: {$bon->code}." . ($bon->message ? " Message: {$bon->message}" : ''),
            'type'       => 'bon_paiement',
            'demande_id' => $bon->demande_id,
        ]);

        return redirect()->route('bons.show', $bon)->with('success', "Bon de paiement envoyé avec succès ! Code: {$bon->code}");
    }

    // Détail d'un bon
    public function show(BonPaiement $bon)
    {
        $this->autoriser($bon);
        return view('bons.show', compact('bon'));
    }

    // Liste des bons (achetés ou reçus)
    public function index()
    {
        $achetes = BonPaiement::where('acheteur_id', auth()->id())->with(['beneficiaire', 'demande'])->latest()->get();
        $recus   = BonPaiement::where('beneficiaire_id', auth()->id())->with(['acheteur', 'demande'])->latest()->get();
        return view('bons.index', compact('achetes', 'recus'));
    }

    // Utiliser un bon pour payer une demande
    public function utiliser(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $bon = BonPaiement::where('code', strtoupper($request->code))
            ->where('beneficiaire_id', auth()->id())
            ->first();

        if (!$bon) {
            return back()->withErrors(['code' => 'Code invalide ou ce bon ne vous appartient pas.']);
        }

        if (!$bon->estValide()) {
            return back()->withErrors(['code' => 'Ce bon est déjà utilisé ou expiré.']);
        }

        $bon->update(['statut' => 'utilise', 'utilise_le' => now()]);

        // Notifier l'acheteur
        Notification::create([
            'user_id' => $bon->acheteur_id,
            'titre'   => '✅ Votre bon a été utilisé',
            'message' => auth()->user()->name . " a utilisé votre bon de paiement de {$bon->montant} FCFA (code: {$bon->code}).",
            'type'    => 'bon_utilise',
        ]);

        return back()->with('success', "Bon de {$bon->montant} FCFA appliqué avec succès !");
    }

    private function autoriser(BonPaiement $bon)
    {
        $user = auth()->user();
        if ($user->isAdmin()) return;
        if ($bon->acheteur_id !== $user->id && $bon->beneficiaire_id !== $user->id) abort(403);
    }
}

