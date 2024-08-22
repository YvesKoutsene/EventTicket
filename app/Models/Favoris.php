<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favoris extends Model
{
    use HasFactory;
    protected $fillable = [
        'eve_id',
        'user_id',

    ];

    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'eve_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
