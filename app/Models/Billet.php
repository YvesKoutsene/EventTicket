<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    protected $table = "billets";
    use HasFactory;
    protected $fillable = [
        'eve_id',
        'typ_id',
        'nombre',
        'prix',
        'quota',
        'rest',
        'status',
    ];

    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'eve_id');
    }

    public function typeBillet()
    {
        return $this->belongsTo(TypeBillet::class, 'typ_id');
    }

}
