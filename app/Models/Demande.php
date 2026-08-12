<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'hopital_id', 'banque_de_sang_id', 'groupe_sanguin',
        'quantite', 'urgence', 'statut', 'description', 'date_besoin',
    ];

    protected $casts = ['date_besoin' => 'datetime'];

    public function user()        { return $this->belongsTo(User::class); }
    public function hopital()     { return $this->belongsTo(Hopital::class); }
    public function banqueDeSang(){ return $this->belongsTo(BanqueDeSang::class); }
    public function paiement()    { return $this->hasOne(Paiement::class); }
}

