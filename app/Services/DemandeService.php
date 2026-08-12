<?php

namespace App\Services;

use App\Models\BanqueDeSang;
use App\Models\Demande;
use App\Models\Hopital;
use App\Models\Notification;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Carbon;

class DemandeService
{
    const COMPATIBILITES = [
        'A+'  => ['A+', 'A-', 'O+', 'O-'],
        'A-'  => ['A-', 'O-'],
        'B+'  => ['B+', 'B-', 'O+', 'O-'],
        'B-'  => ['B-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'AB-' => ['A-', 'B-', 'AB-', 'O-'],
        'O+'  => ['O+', 'O-'],
        'O-'  => ['O-'],
    ];

    const STATUTS_LIBELLES = [
        'en_attente' => 'En attente',
        'en_cours'   => 'En cours de traitement',
        'satisfaite' => 'Satisfaite',
        'annulee'    => 'Annulée',
    ];

    public function traiterNouvelleDemande(Demande $demande): void
    {
        if ($demande->hopital_id) {
            $this->notifierHopital($demande);
        }

        $banqueStock = Stock::where('groupe_sanguin', $demande->groupe_sanguin)
            ->where('quantite', '>=', $demande->quantite)
            ->with('banqueDeSang')
            ->first();

        if ($banqueStock) {
            $demande->update([
                'banque_de_sang_id' => $banqueStock->banqueDeSang->id,
                'statut'            => 'en_cours',
            ]);
            $this->notifierBanqueDeSang($demande, $banqueStock->banqueDeSang);
        } else {
            $this->notifierDonneurs($demande);
            $this->notifierBanquesSansStock($demande);
        }

        $this->notifierAdmins($demande);
    }

    public function notifierChangementStatut(Demande $demande, string $ancienStatut, string $nouveauStatut): void
    {
        $now = Carbon::now()->toDateTimeString();
        $libelle = self::STATUTS_LIBELLES[$nouveauStatut] ?? $nouveauStatut;

        $notifications = [[
            'user_id'    => $demande->user_id,
            'titre'      => 'Mise à jour de votre demande',
            'message'    => "Votre demande de sang {$demande->groupe_sanguin} ({$demande->quantite}ml) est maintenant: {$libelle}",
            'type'       => 'statut_demande',
            'demande_id' => $demande->id,
            'lue'        => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]];

        if ($demande->hopital_id && $demande->hopital?->user) {
            $notifications[] = [
                'user_id'    => $demande->hopital->user->id,
                'titre'      => "Mise à jour d'une demande",
                'message'    => "La demande #{$demande->id} ({$demande->groupe_sanguin}, {$demande->quantite}ml) est maintenant: {$libelle}",
                'type'       => 'statut_demande',
                'demande_id' => $demande->id,
                'lue'        => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($demande->banque_de_sang_id && $demande->banqueDeSang?->user) {
            $notifications[] = [
                'user_id'    => $demande->banqueDeSang->user->id,
                'titre'      => "Mise à jour d'une demande assignée",
                'message'    => "La demande #{$demande->id} qui vous était assignée est maintenant: {$libelle}",
                'type'       => 'statut_demande',
                'demande_id' => $demande->id,
                'lue'        => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($nouveauStatut === 'satisfaite') {
            $notifications[] = [
                'user_id'    => $demande->user_id,
                'titre'      => '🎉 Demande satisfaite !',
                'message'    => "Bonne nouvelle ! Votre demande de sang {$demande->groupe_sanguin} a été satisfaite. Vous pouvez procéder au paiement si nécessaire.",
                'type'       => 'demande_satisfaite',
                'demande_id' => $demande->id,
                'lue'        => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $notifications = array_merge($notifications, $this->buildRemerciementsDonneurs($demande, $now));
        }

        if ($nouveauStatut === 'annulee') {
            $notifications[] = [
                'user_id'    => $demande->user_id,
                'titre'      => 'Demande annulée',
                'message'    => "Votre demande de sang {$demande->groupe_sanguin} a été annulée. Pour plus d'informations, contactez votre hôpital.",
                'type'       => 'demande_annulee',
                'demande_id' => $demande->id,
                'lue'        => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Notification::insert($notifications);
    }

    private function notifierHopital(Demande $demande): void
    {
        $hopital = Hopital::find($demande->hopital_id);
        if (!$hopital?->user) return;

        Notification::create([
            'user_id'    => $hopital->user->id,
            'titre'      => 'Nouvelle demande de sang',
            'message'    => "Une demande de sang {$demande->groupe_sanguin} ({$demande->quantite}ml) a été soumise et assignée à votre hôpital. Urgence: {$demande->urgence}.",
            'type'       => 'demande_hopital',
            'demande_id' => $demande->id,
        ]);
    }

    private function notifierBanqueDeSang(Demande $demande, BanqueDeSang $banque): void
    {
        if (!$banque->user) return;

        Notification::create([
            'user_id'    => $banque->user->id,
            'titre'      => 'Demande assignée à votre banque',
            'message'    => "Une demande de sang {$demande->groupe_sanguin} ({$demande->quantite}ml) vous a été assignée. Urgence: {$demande->urgence}.",
            'type'       => 'demande_banque',
            'demande_id' => $demande->id,
        ]);
    }

    private function notifierAdmins(Demande $demande): void
    {
        $now = Carbon::now()->toDateTimeString();
        $admins = User::where('role', 'admin')->pluck('id');

        $notifications = $admins->map(fn($adminId) => [
            'user_id'    => $adminId,
            'titre'      => 'Nouvelle demande sur la plateforme',
            'message'    => "Nouvelle demande de {$demande->user->name} pour du sang {$demande->groupe_sanguin} ({$demande->quantite}ml). Urgence: {$demande->urgence}.",
            'type'       => 'demande_admin',
            'demande_id' => $demande->id,
            'lue'        => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    private function notifierDonneurs(Demande $demande): void
    {
        $now = Carbon::now()->toDateTimeString();
        $groupesCompatibles = self::COMPATIBILITES[$demande->groupe_sanguin] ?? [$demande->groupe_sanguin];

        $donneurs = User::where('role', 'donneur')
            ->whereIn('groupe_sanguin', $groupesCompatibles)
            ->whereHas('donneur', fn($q) => $q->where('disponible', true)->where('notifications_actives', true))
            ->pluck('id');

        $notifications = $donneurs->map(fn($donneurId) => [
            'user_id'    => $donneurId,
            'titre'      => "Urgence: Besoin de sang {$demande->groupe_sanguin}",
            'message'    => "Stock insuffisant ! Un patient a besoin de sang {$demande->groupe_sanguin} ({$demande->quantite}ml). Urgence: {$demande->urgence}. Votre don peut sauver une vie.",
            'type'       => 'alerte_don',
            'demande_id' => $demande->id,
            'lue'        => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    private function notifierBanquesSansStock(Demande $demande): void
    {
        $now = Carbon::now()->toDateTimeString();

        $banqueIds = BanqueDeSang::where('actif', true)
            ->whereHas('user')
            ->with('user')
            ->get()
            ->pluck('user.id');

        $notifications = $banqueIds->map(fn($userId) => [
            'user_id'    => $userId,
            'titre'      => 'Stock insuffisant - Nouvelle demande',
            'message'    => "Une demande de sang {$demande->groupe_sanguin} ({$demande->quantite}ml) ne peut pas être satisfaite par manque de stock. Considérez organiser une collecte.",
            'type'       => 'stock_insuffisant',
            'demande_id' => $demande->id,
            'lue'        => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    private function buildRemerciementsDonneurs(Demande $demande, string $now): array
    {
        $groupesCompatibles = self::COMPATIBILITES[$demande->groupe_sanguin] ?? [$demande->groupe_sanguin];

        return User::where('role', 'donneur')
            ->whereIn('groupe_sanguin', $groupesCompatibles)
            ->whereHas('donneur', fn($q) => $q->where('notifications_actives', true))
            ->pluck('id')
            ->map(fn($donneurId) => [
                'user_id'    => $donneurId,
                'titre'      => '🙏 Merci pour votre générosité !',
                'message'    => "Grâce aux donneurs comme vous, une demande de sang {$demande->groupe_sanguin} a été satisfaite. Vous contribuez à sauver des vies !",
                'type'       => 'remerciement',
                'demande_id' => $demande->id,
                'lue'        => false,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->toArray();
    }
}

