<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel utama inventaris barang laboratorium.
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            // User pembuat data barang, null jika user terhapus.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('kode_barang')->unique();
            $table->string('nama');
            $table->string('kategori');
            $table->string('lokasi');
            $table->unsignedInteger('jumlah')->default(0);
            $table->enum('kondisi', ['baik', 'rusak ringan', 'rusak berat'])->default('baik');
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
