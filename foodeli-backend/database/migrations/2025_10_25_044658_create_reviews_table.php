<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // Restaurant review
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            
            // Rider review
            $table->tinyInteger('rider_rating')->nullable();
            $table->text('rider_comment')->nullable();
            
            // Status
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_featured')->default(false);
            
            // Admin response
            $table->text('admin_response')->nullable();
            $table->timestamp('admin_responded_at')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('restaurant_id');
            $table->index('order_id');
            $table->index(['restaurant_id', 'is_approved']);
            $table->index('rating');
            
            // One review per order
            $table->unique(['order_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};