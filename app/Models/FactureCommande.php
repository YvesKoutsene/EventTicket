<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureCommande extends Model
{
    use HasFactory;
    protected $fillable = [
        'bil_id',
        'nombreTicket',
        'prixTotal',
        'user_id',

    ];

    public function billet()
    {
        return $this->belongsTo(Billet::class, 'bil_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'fac_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
