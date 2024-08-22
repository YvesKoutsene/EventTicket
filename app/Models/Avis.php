<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Avis extends Model
{
    use HasFactory;
    protected $fillable = [
        'eve_id',
        'note',
        'comment',
        'user_id',
        'status',
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
