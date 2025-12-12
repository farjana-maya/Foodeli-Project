<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            
            // Vehicle
            $table->enum('vehicle_type', ['bicycle', 'motorcycle', 'car', 'scooter'])->default('motorcycle');
            $table->string('vehicle_number', 50)->nullable();
            $table->string('vehicle_model', 100)->nullable();
            
            // License
            $table->string('license_number', 100)->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('license_image', 255)->nullable();
            
            // Identity
            $table->string('id_card_number', 100)->nullable();
            $table->string('id_card_image', 255)->nullable();
            
            // Status
            $table->boolean('is_available')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            
            // Location
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('location_updated_at')->nullable();
            
            // Stats
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_deliveries')->default(0);
            $table->integer('completed_deliveries')->default(0);
            $table->integer('cancelled_deliveries')->default(0);
            
            // Banking
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->string('account_holder_name', 255)->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index(['is_available', 'is_verified']);
            $table->index(['current_latitude', 'current_longitude']);
            $table->index('rating');
        });
    }

    public function down()
    {
        Schema::dropIfExists('riders');
    }
};
