<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('label', ['home', 'work', 'other'])->default('other');
            $table->string('recipient_name', 255)->nullable();
            $table->string('phone', 20)->nullable();
            
            $table->text('address_line');
            $table->string('building_name', 255)->nullable();
            $table->string('floor_unit', 100)->nullable();
            $table->string('city', 100);
            $table->string('area', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            
            // Geolocation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->text('delivery_instructions')->nullable();
            $table->boolean('is_default')->default(false);
            
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index(['user_id', 'is_default']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};