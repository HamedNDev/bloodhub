<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionCagnotte extends Model
{
    protected $table = 'contributions_cagnotte';

    protected $fillable = [
        'cagnotte_id', 'contributeur_id', 'nom_contributeur',
        'montant', 'methode', 'message', 'anonyme', 'statut', 'reference',
    ];

    protected $casts = ['anonyme' => 'boolean'];

    public function cagnotte()      { return $this->belongsTo(Cagnotte::class); }
    public function contributeur()  { return $this->belongsTo(User::class, 'contributeur_id'); }

    public function nomAffiche(): string
    {
        if ($this->anonyme) return 'Anonyme';
        return $this->contributeur?->name ?? $this->nom_contributeur ?? 'Inconnu';
    }
}

