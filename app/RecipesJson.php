<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipesJson extends Model
{
    protected $table = 'recipes_json';

    protected $fillable = [
        'name', 'attributes',
    ];
    
    protected $casts = [
        'attributes' => 'array'
    ];
}
