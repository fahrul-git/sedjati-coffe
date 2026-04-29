<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->string('item_option')->nullable()->after('serving_type');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropColumn('item_option');
        });
    }
};
