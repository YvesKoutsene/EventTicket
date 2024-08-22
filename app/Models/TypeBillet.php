<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeBillet extends Model
{
    protected $table = 'types_billets';
    use HasFactory;
    protected $fillable = [
        'nom',
        'description',
    ];

    public function billets()
    {
        return $this->hasMany(Billet::class, 'typ_id');
    }

}
