<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            
            // Relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('rider_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('set null');
            
            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 8, 2)->default(0.00);
            $table->decimal('discount', 8, 2)->default(0.00);
            $table->decimal('tax', 8, 2)->default(0.00);
            $table->decimal('tip', 8, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            
            // Status
            $table->enum('status', [
                'pending', 'confirmed', 'preparing', 'ready', 
                'picked_up', 'on_the_way', 'delivered', 'cancelled', 'rejected'
            ])->default('pending');
            
            // Payment
            $table->enum('payment_method', ['cash', 'card', 'wallet', 'online'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id', 255)->nullable();
            
            // Address snapshot
            $table->json('delivery_address');
            
            // Notes
            $table->text('special_instructions')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Scheduling
            $table->timestamp('scheduled_at')->nullable();
            
            // Lifecycle timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('preparing_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('order_number');
            $table->index('user_id');
            $table->index('restaurant_id');
            $table->index('rider_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index(['user_id', 'status']);
            $table->index(['restaurant_id', 'status']);
            $table->index(['rider_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};