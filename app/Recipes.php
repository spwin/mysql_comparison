<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipes extends Model
{
    protected $table = 'recipes';

    protected $fillable = [
        'name'
    ];

    public function recipe_fields()
	{
	    return $this->belongsToMany('App\RecipeFields', 'recipe_values')->withPivot('value');
	}
}
