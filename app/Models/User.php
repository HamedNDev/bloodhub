<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'telephone', 'role',
        'groupe_sanguin', 'ville', 'quartier', 'latitude', 'longitude',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    public function isAdmin()         { return $this->role === 'admin'; }
    public function isPatient()       { return $this->role === 'patient'; }
    public function isDonneur()       { return $this->role === 'donneur'; }
    public function isHopital()       { return $this->role === 'hopital'; }
    public function isBanqueDeSang()  { return $this->role === 'banque_de_sang'; }

    public function donneur()      { return $this->hasOne(Donneur::class); }
    public function hopital()      { return $this->hasOne(Hopital::class); }
    public function banqueDeSang() { return $this->hasOne(BanqueDeSang::class); }
    public function demandes()      { return $this->hasMany(Demande::class); }
    public function paiements()     { return $this->hasMany(Paiement::class); }
    public function notifications() { return $this->hasMany(Notification::class); }
}

