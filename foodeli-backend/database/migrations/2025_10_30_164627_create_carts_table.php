<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id', 255)->nullable();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            
            $table->integer('quantity')->default(1);
            $table->json('variations')->nullable();
            $table->text('special_instructions')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('session_id');
            $table->index(['user_id', 'restaurant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};