<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotTableRecipeValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_values', function (Blueprint $table) {
            $table->integer('recipe_fields_id')->unsigned();
            $table->integer('recipes_id')->unsigned();
            $table->integer('value');
            $table->timestamps();

            $table->foreign('recipe_fields_id')->references('id')->on('recipe_fields')->onDelete('cascade');
            $table->foreign('recipes_id')->references('id')->on('recipes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_values');
    }
}
