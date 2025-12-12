<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->string('cuisine_type', 100)->nullable();
            
            // Location
            $table->text('address');
            $table->string('city', 100);
            $table->string('postal_code', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Contact
            $table->string('phone', 20);
            $table->string('email', 255)->nullable();
            
            // Media
            $table->string('logo', 255)->nullable();
            $table->string('cover_image', 255)->nullable();
            
            // Ratings
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            
            // Delivery
            $table->integer('delivery_time')->default(30);
            $table->decimal('delivery_fee', 8, 2)->default(0.00);
            $table->decimal('min_order_amount', 8, 2)->default(0.00);
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            
            // Hours
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('owner_id');
            $table->index('slug');
            $table->index(['is_active', 'is_featured']);
            $table->index('city');
            $table->index('rating');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
};
