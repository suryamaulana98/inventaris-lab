<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        // Kumpulan KPI utama untuk ringkasan dashboard inventaris.
        $totalBarang = Item::count();
        $totalUnit = Item::sum('jumlah');
        $stokMenipis = Item::whereColumn('jumlah', '<=', 'min_stok')->where('jumlah', '>', 0)->count();
        $stokHabis = Item::where('jumlah', 0)->count();
        $barangDipinjam = Item::where('is_dipinjam', true)->count();
        $barangBaik = Item::where('kondisi', 'baik')->count();
        $barangRusak = Item::whereIn('kondisi', ['rusak ringan', 'rusak berat'])->count();
        // Persentase risiko dihitung dari proporsi item menipis terhadap total item.
        $persentaseRisikoStok = $totalBarang > 0 ? round(($stokMenipis / $totalBarang) * 100, 1) : 0;
        $totalMutasiHariIni = InventoryMovement::whereDate('created_at', now()->toDateString())->count();

        // Daftar prioritas barang untuk tindakan restock cepat.
        $warningItems = Item::whereColumn('jumlah', '<=', 'min_stok')
            ->orderBy('jumlah')
            ->latest()
            ->take(10)
            ->get();

        // Aktivitas stok terbaru untuk audit dan monitoring harian.
        $recentMovements = InventoryMovement::with(['item:id,nama,kode_barang', 'user:id,name'])
            ->latest()
            ->take(8)
            ->get();

        // Kirim semua data ringkasan ke tampilan dashboard.
        return view('dashboard', compact(
            'totalBarang',
            'totalUnit',
            'stokMenipis',
            'stokHabis',
            'barangDipinjam',
            'barangBaik',
            'barangRusak',
            'persentaseRisikoStok',
            'totalMutasiHariIni',
            'warningItems',
            'recentMovements'
        ));
    }
}
