<?php

namespace App\Http\Controllers;

use App\Models\BanqueDeSang;
use App\Models\Demande;
use App\Models\Hopital;
use App\Services\DemandeService;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function __construct(private DemandeService $service) {}

    public function index()
    {
        $user = auth()->user();

        $demandes = match ($user->role) {
            'admin'          => Demande::with(['user', 'hopital', 'banqueDeSang'])->latest()->get(),
            'hopital'        => Demande::where('hopital_id', $user->hopital->id ?? 0)->with(['banqueDeSang'])->latest()->get(),
            'banque_de_sang' => Demande::where('banque_de_sang_id', $user->banqueDeSang->id ?? 0)->with(['user', 'hopital'])->latest()->get(),
            default          => Demande::where('user_id', $user->id)->latest()->get(),
        };

        return view('demandes.index', compact('demandes'));
    }

    public function create()
    {
        $hopitaux = Hopital::where('actif', true)->get();
        $banques  = BanqueDeSang::where('actif', true)->get();
        $groupes  = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        return view('demandes.create', compact('hopitaux', 'banques', 'groupes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'groupe_sanguin' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantite'       => 'required|integer|min:1',
            'urgence'        => 'required|in:normale,urgente,critique',
            'description'    => 'nullable|string|max:500',
            'date_besoin'    => 'nullable|date',
            'hopital_id'     => 'nullable|exists:hopitaux,id',
        ]);

        $demande = Demande::create([
            'user_id'        => auth()->id(),
            'hopital_id'     => $request->hopital_id ?: null,
            'groupe_sanguin' => $request->groupe_sanguin,
            'quantite'       => $request->quantite,
            'urgence'        => $request->urgence,
            'description'    => $request->description,
            'date_besoin'    => $request->date_besoin,
            'statut'         => 'en_attente',
        ]);

        $this->service->traiterNouvelleDemande($demande);

        return redirect()->route('demandes.index')
            ->with('success', 'Demande créée avec succès. Les parties concernées ont été notifiées.');
    }

    public function show(Demande $demande)
    {
        $this->autoriser($demande);
        return view('demandes.show', compact('demande'));
    }

    public function updateStatut(Request $request, Demande $demande)
    {
        $request->validate(['statut' => 'required|in:en_attente,en_cours,satisfaite,annulee']);

        $ancienStatut = $demande->statut;
        $demande->update(['statut' => $request->statut]);

        $this->service->notifierChangementStatut($demande, $ancienStatut, $request->statut);

        return back()->with('success', 'Statut mis à jour. Les parties concernées ont été notifiées.');
    }

    private function autoriser(Demande $demande): void
    {
        $user = auth()->user();
        if ($user->isAdmin()) return;
        if ($user->isPatient() && $demande->user_id !== $user->id) abort(403);
        if ($user->isHopital() && $demande->hopital_id !== ($user->hopital->id ?? null)) abort(403);
    }
}

