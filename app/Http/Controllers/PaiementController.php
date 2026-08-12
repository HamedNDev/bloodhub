<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Paiement;
use App\Services\CamPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    public function __construct(private CamPayService $campay) {}

    public function index()
    {
        $paiements = Paiement::where('user_id', auth()->id())
            ->with('demande')->latest()->get();
        return view('paiements.index', compact('paiements'));
    }

    public function create(Demande $demande)
    {
        if ($demande->user_id !== auth()->id()) abort(403);
        if ($demande->paiement?->statut === 'confirme') {
            return redirect()->route('paiements.index')->with('success', 'Paiement déjà effectué.');
        }
        return view('paiements.create', compact('demande'));
    }

    /**
     * Initier le paiement via CamPay (lien de paiement)
     */
    public function store(Request $request, Demande $demande)
    {
        if ($demande->user_id !== auth()->id()) abort(403);

        $request->validate([
            'montant'          => 'required|integer|min:1|max:25',
            'methode'          => 'required|in:orange_money,mtn_momo',
            'numero_telephone' => 'required|string|max:20',
        ]);

        $reference = 'BH-' . strtoupper(Str::random(8));

        // Formater le numéro : 237XXXXXXXXX
        $phone = preg_replace('/\D/', '', $request->numero_telephone);
        if (!str_starts_with($phone, '237')) {
            $phone = '237' . ltrim($phone, '0');
        }

        // Créer le paiement en base (statut en_attente)
        $paiement = Paiement::updateOrCreate(
            ['demande_id' => $demande->id, 'user_id' => auth()->id()],
            [
                'montant'          => $request->montant,
                'methode'          => $request->methode,
                'numero_telephone' => $phone,
                'reference'        => $reference,
                'statut'           => 'en_attente',
            ]
        );

        try {
            // Générer un lien de paiement CamPay
            $result = $this->campay->getPaymentLink([
                'amount'      => $request->montant,
                'description' => "VitaFlow - Demande #{$demande->id} - Groupe {$demande->groupe_sanguin}",
                'reference'   => $reference,
                'phone'       => $phone,
                'first_name'  => auth()->user()->name,
                'email'       => auth()->user()->email,
            ]);

            // Sauvegarder la référence CamPay
            $paiement->update(['campay_reference' => $result['reference'] ?? null]);

            // Rediriger vers le lien de paiement CamPay
            return redirect($result['link']);

        } catch (\Exception $e) {
            $paiement->update(['statut' => 'echoue']);
            return back()->with('error', 'Erreur de paiement : ' . $e->getMessage());
        }
    }

    /**
     * Callback après paiement réussi (redirect_url)
     * CamPay redirige l'utilisateur ici — on vérifie le statut réel via l'API
     */
    public function callback(Request $request)
    {
        $reference = $request->get('reference');

        if (!$reference) {
            return redirect()->route('paiements.index')->with('error', 'Référence de paiement manquante.');
        }

        $paiement = Paiement::where('campay_reference', $reference)
            ->orWhere('reference', $reference)
            ->first();

        if (!$paiement) {
            return redirect()->route('paiements.index')->with('error', 'Paiement introuvable.');
        }

        try {
            // Vérifier le statut réel auprès de CamPay
            $status = $this->campay->getTransactionStatus($reference);

            if (($status['status'] ?? '') === 'SUCCESSFUL') {
                $paiement->update([
                    'statut'        => 'confirme',
                    'date_paiement' => now(),
                ]);
                return redirect()->route('paiements.show', $paiement)
                    ->with('success', "Paiement confirmé ! Référence : {$paiement->reference}");
            } else {
                $paiement->update(['statut' => 'echoue']);
                return redirect()->route('paiements.index')
                    ->with('error', 'Le paiement n\'a pas abouti. Statut : ' . ($status['status'] ?? 'inconnu'));
            }
        } catch (\Exception $e) {
            return redirect()->route('paiements.index')
                ->with('error', 'Impossible de vérifier le paiement : ' . $e->getMessage());
        }
    }

    /**
     * Callback après paiement échoué (failure_redirect_url)
     */
    public function echec(Request $request)
    {
        $reference = $request->get('reference');
        if ($reference) {
            Paiement::where('campay_reference', $reference)
                ->update(['statut' => 'echoue']);
        }
        return redirect()->route('paiements.index')
            ->with('error', 'Le paiement a échoué ou a été annulé.');
    }

    /**
     * Vérifier manuellement le statut d'un paiement
     */
    public function verifierStatut(Paiement $paiement)
    {
        if ($paiement->user_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);

        if (!$paiement->campay_reference) {
            return back()->with('error', 'Aucune référence CamPay pour ce paiement.');
        }

        try {
            $status = $this->campay->getTransactionStatus($paiement->campay_reference);

            if (($status['status'] ?? '') === 'SUCCESSFUL') {
                $paiement->update(['statut' => 'confirme', 'date_paiement' => now()]);
                return back()->with('success', 'Paiement confirmé.');
            } else {
                return back()->with('error', 'Statut : ' . ($status['status'] ?? 'EN ATTENTE'));
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Paiement $paiement)
    {
        if ($paiement->user_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
        return view('paiements.show', compact('paiement'));
    }
}

