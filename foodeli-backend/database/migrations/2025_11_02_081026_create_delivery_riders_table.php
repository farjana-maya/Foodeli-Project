<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->date('date_of_birth');
            $table->string('nid_number');
            $table->string('driving_license');
            $table->string('vehicle_type'); // bike, car, bicycle
            $table->string('vehicle_model');
            $table->string('vehicle_number');
            $table->string('profile_photo')->nullable();
            $table->string('nid_photo')->nullable();
            $table->string('license_photo')->nullable();
            $table->string('vehicle_photo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(false);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_deliveries')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_riders');
    }
};
