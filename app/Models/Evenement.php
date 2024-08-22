<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $table = 'evenements';
    use HasFactory;
    protected $fillable = [
        'cat_id',
        'nom',
        'dateDebut',
        'heure',
        'place',
        'placeRestant',
        'lieu',
        'description',
        'image',
        'status',
        'motif',
        'datePublication',
        'dateFin',
        'user_id',
        'type',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieEvenement::class, 'cat_id');
    }

    public function billets()
    {
        return $this->hasMany(Billet::class, 'eve_id');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'eve_id');
    }

    public function favoris()
    {
        return $this->hasMany(Favoris::class, 'eve_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
