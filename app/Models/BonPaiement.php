<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonPaiement extends Model
{
    protected $table = 'bons_paiement';

    protected $fillable = [
        'demande_id', 'acheteur_id', 'beneficiaire_id',
        'montant', 'code', 'message', 'methode',
        'statut', 'expire_le', 'utilise_le',
    ];

    protected $casts = [
        'expire_le'  => 'datetime',
        'utilise_le' => 'datetime',
    ];

    public function demande()      { return $this->belongsTo(Demande::class); }
    public function acheteur()     { return $this->belongsTo(User::class, 'acheteur_id'); }
    public function beneficiaire() { return $this->belongsTo(User::class, 'beneficiaire_id'); }

    public function estValide(): bool
    {
        return $this->statut === 'actif' &&
               ($this->expire_le === null || $this->expire_le->isFuture());
    }
}

