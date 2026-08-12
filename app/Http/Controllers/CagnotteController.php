<?php

namespace App\Http\Controllers;

use App\Models\Cagnotte;
use App\Models\ContributionCagnotte;
use App\Models\Demande;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CagnotteController extends Controller
{
    // Page publique listant toutes les cagnottes actives
    public function publique()
    {
        $cagnottes = Cagnotte::where('statut', 'active')
            ->with(['demande', 'createur', 'contributions'])
            ->latest()
            ->paginate(12);
        return view('cagnottes.publique', compact('cagnottes'));
    }

    // Page publique d'une cagnotte (accessible sans connexion)
    public function voir(string $slug)
    {
        $cagnotte = Cagnotte::where('slug', $slug)
            ->with(['demande', 'createur', 'contributions.contributeur'])
            ->firstOrFail();
        return view('cagnottes.voir', compact('cagnotte'));
    }

    // Liste des cagnottes de l'utilisateur connecté
    public function index()
    {
        $cagnottes = Cagnotte::where('createur_id', auth()->id())
            ->with(['demande', 'contributions'])
            ->latest()
            ->get();
        return view('cagnottes.index', compact('cagnottes'));
    }

    // Formulaire de création
    public function create(Request $request)
    {
        $demande = null;
        if ($request->demande_id) {
            $demande = Demande::findOrFail($request->demande_id);
        }
        $demandes = Demande::where('user_id', auth()->id())
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->get();
        return view('cagnottes.create', compact('demande', 'demandes'));
    }

    // Enregistrer la cagnotte
    public function store(Request $request)
    {
        $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'objectif'    => 'required|numeric|min:1000',
            'demande_id'  => 'nullable|exists:demandes,id',
            'expire_le'   => 'nullable|date|after:today',
        ]);

        $cagnotte = Cagnotte::create([
            'demande_id'       => $request->demande_id,
            'createur_id'      => auth()->id(),
            'titre'            => $request->titre,
            'description'      => $request->description,
            'objectif'         => $request->objectif,
            'montant_collecte' => 0,
            'slug'             => Str::slug($request->titre) . '-' . Str::random(6),
            'statut'           => 'active',
            'expire_le'        => $request->expire_le,
        ]);

        return redirect()->route('cagnottes.voir', $cagnotte->slug)
            ->with('success', 'Cagnotte créée ! Partagez le lien pour collecter des fonds.');
    }

    // Contribuer à une cagnotte (accessible sans connexion)
    public function contribuer(Request $request, string $slug)
    {
        $cagnotte = Cagnotte::where('slug', $slug)->firstOrFail();

        if ($cagnotte->statut !== 'active') {
            return back()->withErrors(['error' => 'Cette cagnotte est fermée.']);
        }

        $request->validate([
            'montant'          => 'required|numeric|min:100',
            'methode'          => 'required|in:orange_money,mtn_momo,autre',
            'message'          => 'nullable|string|max:255',
            'anonyme'          => 'boolean',
            'nom_contributeur' => 'required_if:anonyme,false|nullable|string|max:100',
        ]);

        $contribution = ContributionCagnotte::create([
            'cagnotte_id'      => $cagnotte->id,
            'contributeur_id'  => auth()->id() ?? null,
            'nom_contributeur' => $request->nom_contributeur,
            'montant'          => $request->montant,
            'methode'          => $request->methode,
            'message'          => $request->message,
            'anonyme'          => $request->boolean('anonyme'),
            'statut'           => 'confirme',
            'reference'        => strtoupper(Str::random(12)),
        ]);

        // Mettre à jour le montant collecté
        $cagnotte->increment('montant_collecte', $request->montant);
        $cagnotte->refresh();

        // Fermer si objectif atteint
        if ($cagnotte->montant_collecte >= $cagnotte->objectif) {
            $cagnotte->update(['statut' => 'atteinte']);
        }

        // Notifier le créateur
        $nomContrib = $contribution->nomAffiche();
        Notification::create([
            'user_id'    => $cagnotte->createur_id,
            'titre'      => '💰 Nouvelle contribution à votre cagnotte !',
            'message'    => "{$nomContrib} a contribué {$request->montant} FCFA à votre cagnotte \"{$cagnotte->titre}\". Total: {$cagnotte->montant_collecte} FCFA.",
            'type'       => 'contribution_cagnotte',
            'demande_id' => $cagnotte->demande_id,
        ]);

        return back()->with('success', "Merci pour votre contribution de {$request->montant} FCFA !");
    }

    public function destroy(Cagnotte $cagnotte)
    {
        if ($cagnotte->createur_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
        $cagnotte->update(['statut' => 'fermee']);
        return redirect()->route('cagnottes.index')->with('success', 'Cagnotte fermée.');
    }
}

