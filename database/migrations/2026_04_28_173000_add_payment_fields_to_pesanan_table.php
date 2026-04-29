<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->decimal('paid_amount', 12, 2)->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('paid_amount');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'paid_amount',
                'paid_at',
            ]);
        });
    }
};
