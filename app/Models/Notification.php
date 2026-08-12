<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'titre', 'message', 'type', 'demande_id', 'lue'];

    protected $casts = ['lue' => 'boolean'];

    public function user()    { return $this->belongsTo(User::class); }
    public function demande() { return $this->belongsTo(Demande::class); }
}

