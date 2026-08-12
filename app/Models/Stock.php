<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'banque_de_sang_id', 'groupe_sanguin', 'quantite',
        'seuil_alerte', 'derniere_mise_a_jour',
    ];

    protected $casts = ['derniere_mise_a_jour' => 'datetime'];

    public function banqueDeSang() { return $this->belongsTo(BanqueDeSang::class); }

    public function estCritique() { return $this->quantite <= $this->seuil_alerte; }
}

