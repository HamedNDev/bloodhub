<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cagnotte extends Model
{
    protected $fillable = [
        'demande_id', 'createur_id', 'titre', 'description',
        'objectif', 'montant_collecte', 'slug', 'statut', 'expire_le',
    ];

    protected $casts = [
        'expire_le'        => 'datetime',
        'objectif'         => 'decimal:2',
        'montant_collecte' => 'decimal:2',
    ];

    public function demande()       { return $this->belongsTo(Demande::class); }
    public function createur()      { return $this->belongsTo(User::class, 'createur_id'); }
    public function contributions() { return $this->hasMany(ContributionCagnotte::class); }

    public function pourcentage(): int
    {
        if ($this->objectif <= 0) return 0;
        return min(100, (int) (($this->montant_collecte / $this->objectif) * 100));
    }

    public function resteACollecterAttribute()
    {
        return max(0, $this->objectif - $this->montant_collecte);
    }
}

