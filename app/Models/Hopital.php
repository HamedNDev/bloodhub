<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hopital extends Model
{
    use HasFactory;

    protected $table = 'hopitaux';

    protected $fillable = [
        'user_id', 'nom', 'adresse', 'ville', 'region',
        'telephone', 'latitude', 'longitude', 'actif',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function demandes() { return $this->hasMany(Demande::class); }
}

