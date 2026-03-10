<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('status');
                $table->index('payment_status');
            }
            if (! Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
                $table->index('payment_method');
            }
            if (! Schema::hasColumn('orders', 'midtrans_snap_token')) {
                $table->string('midtrans_snap_token')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('orders', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->after('midtrans_snap_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['midtrans_snap_token', 'midtrans_transaction_id'];

            if (Schema::hasColumn('orders', 'payment_status')) {
                $columns[] = 'payment_status';
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $columns[] = 'payment_method';
            }

            $table->dropColumn($columns);
        });
    }
};
