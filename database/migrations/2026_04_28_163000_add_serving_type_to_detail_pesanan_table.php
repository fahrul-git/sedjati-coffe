<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->enum('serving_type', ['es', 'panas'])
                ->default('panas')
                ->after('product_name');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropColumn('serving_type');
        });
    }
};
