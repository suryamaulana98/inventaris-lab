<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Menandai apakah item sedang dipinjam atau tersedia.
            $table->boolean('is_dipinjam')->default(false)->after('min_stok');
            // Menyimpan nama peminjam aktif saat item dipinjam.
            $table->string('nama_peminjam', 100)->nullable()->after('is_dipinjam');
            // Menyimpan waktu mulai peminjaman.
            $table->timestamp('tanggal_pinjam')->nullable()->after('nama_peminjam');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['is_dipinjam', 'nama_peminjam', 'tanggal_pinjam']);
        });
    }
};
