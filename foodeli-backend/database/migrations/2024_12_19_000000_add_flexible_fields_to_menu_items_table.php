<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Remove category length restriction by changing to text
            $table->text('category')->nullable()->change();
            
            // Add flexible fields for unlimited customization
            $table->json('dietary_info')->nullable(); // vegan, vegetarian, gluten-free, etc.
            $table->string('spice_level')->nullable(); // mild, medium, hot, extra hot
            $table->string('portion_size')->nullable(); // small, medium, large, family
            $table->string('cooking_method')->nullable(); // grilled, fried, baked, steamed
            $table->string('origin_country')->nullable(); // Italian, Chinese, Mexican, etc.
            $table->json('nutritional_info')->nullable(); // protein, carbs, fat, fiber, etc.
            $table->json('tags')->nullable(); // popular, chef-special, new, seasonal, etc.
            
            // Make name field longer
            $table->string('name', 500)->change();
            
            // Make description longer
            $table->text('description', 2000)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('category')->default('main')->change();
            $table->dropColumn([
                'dietary_info',
                'spice_level', 
                'portion_size',
                'cooking_method',
                'origin_country',
                'nutritional_info',
                'tags'
            ]);
            $table->string('name', 255)->change();
        });
    }
};