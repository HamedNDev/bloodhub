<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donneur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date_naissance', 'sexe', 'disponible',
        'dernier_don', 'nombre_dons', 'notifications_actives',
    ];

    protected $casts = [
        'disponible' => 'boolean', 
        'notifications_actives' => 'boolean',
        'dernier_don' => 'datetime',
        'date_naissance' => 'date'
    ];

    public function user() { return $this->belongsTo(User::class); }
}

