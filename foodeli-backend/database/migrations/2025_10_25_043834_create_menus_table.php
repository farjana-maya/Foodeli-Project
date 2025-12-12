<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            
            // Pricing
            $table->decimal('price', 8, 2);
            $table->decimal('discount_price', 8, 2)->nullable();
            
            // Media
            $table->string('image', 255)->nullable();
            
            // Status
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_vegan')->default(false);
            
            // Additional
            $table->integer('preparation_time')->nullable();
            $table->integer('calories')->nullable();
            $table->json('ingredients')->nullable();
            $table->json('allergens')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('restaurant_id');
            $table->index('category_id');
            $table->index('is_available');
            $table->index('is_featured');
            $table->index(['restaurant_id', 'category_id']);
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
