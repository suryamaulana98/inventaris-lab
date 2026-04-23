<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel histori pergerakan stok (masuk/keluar) untuk audit inventaris.
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            // Hapus histori otomatis jika item dihapus.
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            // User pelaku mutasi, null jika user tidak tersedia.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->unsignedInteger('jumlah');
            $table->string('catatan', 255)->nullable();
            $table->timestamps();

            // Indeks untuk mempercepat query histori per item berdasarkan waktu.
            $table->index(['item_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
