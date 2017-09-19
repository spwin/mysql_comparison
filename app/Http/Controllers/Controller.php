<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Recipes;
use App\RecipeFields;
use App\RecipesJson;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function fields(){
    	return view('fields')->with([]);
    }

    public function generate(Request $request){
        $this->dumpTables();
        $options = $this->generateOptions($request);
        $fields = $this->generateFields($options, $request);
        $this->generateRecipes($fields, $request);
        return redirect()->action('Controller@test');
    }

    public function dumpTables(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Recipes::query()->truncate();
        RecipeFields::query()->truncate();
        RecipesJson::query()->truncate();
        DB::table('recipe_values')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function generateOptions($data){
        $options = [];
        $options_count = rand($data->get('options_min', 0), $data->get('options_max', 10));
        for($i = 0; $i < $options_count; $i++){
            $options[] = ['id'=> $i, 'option'=>'Option'.$i];
        }
        return $options;
    }

    public function generateFields($options, $data){
        $fields = [];
        for($i=0;$i<$data->get('fields_numeric_count', 10);$i++){
            $field = new RecipeFields();
            $field->fill([
                'name' => str_random(14),
                'type' => 'numeric',
                'meta_data' => $options
            ]);
            $field->save();
            $fields[] = $field;
        }
        for($i=0;$i<$data->get('fields_string_count', 10);$i++){
            $field = new RecipeFields();
            $field->fill([
                'name' => str_random(14),
                'type' => 'single_select',
                'meta_data' => $options
            ]);
            $field->save();
            $fields[] = $field;
        }
        return $fields;
    }

    public function generateRecipes($fields, $data){
        $recipes = [];
	    for($i=0;$i<$data->get('recipes_count', $data->get('recipes_count', 1000));$i++){
	    	$recipe = new Recipes();
	    	$recipe->fill([
	    		'name' => str_random(14)
 	    	]);
 	    	$recipe->save();

            $recipeJson = new RecipesJson();
            $attributes = new \stdClass();

 	    	foreach($fields as $field){
 	    	    $meta_data = $field->meta_data;
                $name = $field->name;
                $value = $field->type == 'numeric' ? rand(1000,1010) : $meta_data[array_rand($meta_data)]['id'];
                $recipe->recipe_fields()->attach($field->id, ['value' => $value]);
                $attributes->$name = $field->type == 'numeric' ? rand(1000,1010) : $meta_data[array_rand($meta_data)]['id'];
            }

            $recipeJson->fill([
                'name' => str_random(14),
                'attributes' => $attributes
            ]);
            $recipeJson->save();


 	    	$recipe->save();
            $recipes[] = $recipe;
	    }
	    return $recipes;
    }

    public function generateRecipesJson($fields, $data){
        $recipes = [];
        for($i=0;$i<$data->get('recipes_count', $data->get('recipes_count', 1000));$i++){
            $recipe = new RecipesJson();
            $attributes = new \stdClass();
            foreach($fields as $field){
                $meta_data = $field->meta_data;
                $name = $field->name;
                $attributes->$name = $field->type == 'numeric' ? rand(1000,1010) : $meta_data[array_rand($meta_data)]['id'];
            }
            $recipe->fill([
                'name' => str_random(14),
                'attributes' => $attributes
            ]);
            $recipe->save();
            $recipes[] = $recipe;
        }
        return $recipes;
    }

    public function test(){
        $max_numeric = RecipeFields::where(['type' => 'numeric'])->count();
        $max_choices = RecipeFields::where(['type' => 'single_select'])->count();
        return view('test')->with([
            'max_numeric' => $max_numeric,
            'max_choices' => $max_choices
        ]);
    }

    public function performTest(Request $request){
        $fields_numeric = RecipeFields::where(['type' => 'numeric'])->limit($request->get('fields_numeric_selected', 0))->get();
        $fields_strings = RecipeFields::where(['type' => 'single_select'])->limit($request->get('fields_choice_selected', 0))->get();
        $fields_choices = [];
        foreach($fields_strings as $fs){
            $meta_data = $fs->meta_data;
            $fields_choices[] = [
                'id' => $fs->id,
                'name' => $fs->name,
                'value' => $meta_data[array_rand($meta_data)]['id']
            ];
        }
        foreach($fields_numeric as $fn){
            $fields_choices[] = [
                'id' => $fn->id,
                'name' => $fn->name,
                'value' => rand(1000,1010)
            ];
        }

        $time_start = microtime(true);
        if($request->get('type') == 'pivot_joins') {
            // Form Query Type 1
            $query = '';
            foreach ($fields_choices as $key => $fc) {
                $query .= "INNER JOIN recipe_values AS rv_{$key} ON rv_{$key}.recipes_id = r.id AND rv_{$key}.recipe_fields_id = {$fc['id']} AND rv_{$key}.value = {$fc['value']} ";
            }
            $recipes = DB::select("SELECT r.id FROM recipes AS r " . $query . " GROUP BY r.id");
        } elseif($request->get('type') == 'json_columns'){
            // Form Query Type 2
            $query = DB::table('recipes_json');
            foreach ($fields_choices as $key => $fc) {
                $query = $query->where('attributes->'.$fc["name"], $fc['value']);
            }
            $recipes = $query->get();
            $query = $query->toSql();
        } elseif($request->get('type') == 'php_filter'){
            // Form Query Type 3
            echo 'nos tyc a tu nic';
            die();
        }
        echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).'<br/>';
        echo 'Found: '.count($recipes).'<br/><br/>';
        echo "SELECT r.id FROM recipes AS r ".$query;
    }
}
