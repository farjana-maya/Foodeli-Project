<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rename columns to match expected schema
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->renameColumn('user_id', 'customer_id');
            }
            if (Schema::hasColumn('orders', 'total')) {
                $table->renameColumn('total', 'total_amount');
            }
            if (Schema::hasColumn('orders', 'tax')) {
                $table->renameColumn('tax', 'tax_amount');
            }
            if (Schema::hasColumn('orders', 'discount')) {
                $table->renameColumn('discount', 'discount_amount');
            }
            
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('orders', 'items')) {
                $table->json('items')->after('rider_id');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->after('delivery_address');
            }
            if (!Schema::hasColumn('orders', 'payment_transaction_id')) {
                $table->string('payment_transaction_id')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'estimated_delivery_time')) {
                $table->timestamp('estimated_delivery_time')->nullable()->after('special_instructions');
            }
            if (!Schema::hasColumn('orders', 'prepared_at')) {
                $table->timestamp('prepared_at')->nullable()->after('confirmed_at');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Reverse the changes
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->renameColumn('customer_id', 'user_id');
            }
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total_amount', 'total');
            }
            if (Schema::hasColumn('orders', 'tax_amount')) {
                $table->renameColumn('tax_amount', 'tax');
            }
            if (Schema::hasColumn('orders', 'discount_amount')) {
                $table->renameColumn('discount_amount', 'discount');
            }
            
            // Drop added columns
            $table->dropColumn(['items', 'customer_phone', 'payment_transaction_id', 'estimated_delivery_time', 'prepared_at']);
        });
    }
};