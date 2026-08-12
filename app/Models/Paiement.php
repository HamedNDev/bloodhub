<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id', 'user_id', 'montant', 'methode',
        'statut', 'reference', 'campay_reference', 'numero_telephone', 'date_paiement',
    ];

    protected $casts = ['date_paiement' => 'datetime'];

    public function demande() { return $this->belongsTo(Demande::class); }
    public function user()    { return $this->belongsTo(User::class); }
}

