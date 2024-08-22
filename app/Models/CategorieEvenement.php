<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieEvenement extends Model
{
    protected $table = 'categories_evenements';
    use HasFactory;
    protected $fillable = [
        'nom',
        'description',
    ];

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'cat_id');
    }

}
