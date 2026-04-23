<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    // Data mutasi yang diizinkan diisi langsung saat create().
    protected $fillable = [
        'item_id',
        'user_id',
        'tipe',
        'jumlah',
        'catatan',
    ];

    // Jumlah mutasi selalu diperlakukan sebagai angka bulat.
    protected $casts = [
        'jumlah' => 'integer',
    ];

    // Tiap mutasi terkait ke satu barang inventaris.
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // Tiap mutasi dicatat oleh satu user (opsional jika null).
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
