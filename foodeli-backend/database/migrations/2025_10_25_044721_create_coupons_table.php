<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('description', 255)->nullable();
            
            // Discount
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed');
            $table->decimal('discount_value', 8, 2);
            $table->decimal('max_discount', 8, 2)->nullable();
            
            // Usage
            $table->decimal('min_order_amount', 8, 2)->default(0.00);
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_per_user')->default(1);
            $table->integer('used_count')->default(0);
            
            // Validity
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            
            // Restrictions
            $table->json('applicable_restaurants')->nullable();
            $table->json('applicable_categories')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index(['is_active', 'valid_from', 'valid_until']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
