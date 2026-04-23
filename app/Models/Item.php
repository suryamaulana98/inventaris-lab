<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi mass assignment dari form.
    protected $fillable = [
        'user_id',
        'kode_barang',
        'nama',
        'kategori',
        'lokasi',
        'jumlah',
        'min_stok',
        'is_dipinjam',
        'nama_peminjam',
        'tanggal_pinjam',
        'kondisi',
        'foto',
        'deskripsi',
    ];

    // Pastikan nilai stok dibaca sebagai integer.
    protected $casts = [
        'jumlah' => 'integer',
        'min_stok' => 'integer',
        'is_dipinjam' => 'boolean',
        'tanggal_pinjam' => 'datetime',
    ];

    // Satu barang bisa memiliki banyak catatan mutasi stok.
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
