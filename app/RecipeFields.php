<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeFields extends Model
{
    protected $table = 'recipe_fields';

    protected $fillable = [
        'name', 'type', 'meta_data',
    ];
    
    protected $casts = [
        'meta_data' => 'array'
    ];

    public function recipes()
    {
        return $this->belongsToMany('App\Recipes', 'recipe_values')->withPivot('value');
    }
}
