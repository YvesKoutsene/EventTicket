<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'fac_id',
        'numero',
        'status',
        'codeQr',
        'dateExpiration',
    ];

    public function factureCommande()
    {
        return $this->belongsTo(FactureCommande::class, 'fac_id');
    }
}
