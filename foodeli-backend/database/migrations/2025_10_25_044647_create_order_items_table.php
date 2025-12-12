<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            
            // Item snapshot
            $table->string('item_name', 255);
            $table->text('item_description')->nullable();
            $table->string('item_image', 255)->nullable();
            
            // Quantity & Price
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_price', 10, 2);
            
            // Customizations
            $table->json('variations')->nullable();
            $table->text('special_instructions')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('menu_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};