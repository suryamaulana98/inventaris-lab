<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Ambang minimum stok per barang untuk kebutuhan warning dashboard.
            $table->unsignedInteger('min_stok')->default(5)->after('jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('min_stok');
        });
    }
};
