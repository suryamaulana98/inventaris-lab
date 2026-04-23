<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    public function index()
    {
        // Query dinamis untuk mendukung filter, search, dan sort dari halaman data barang.
        $query = Item::query();

        if ($search = request('search')) {
            // Search pada beberapa kolom utama agar pencarian lebih fleksibel.
            $query->where(function ($builder) use ($search) {
                $builder->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($kategori = request('kategori')) {
            $query->where('kategori', $kategori);
        }

        if ($kondisi = request('kondisi')) {
            $query->where('kondisi', $kondisi);
        }

        if ($statusPinjam = request('status_pinjam')) {
            if ($statusPinjam === 'dipinjam') {
                $query->where('is_dipinjam', true);
            }

            if ($statusPinjam === 'tersedia') {
                $query->where('is_dipinjam', false);
            }
        }

        if ($status = request('status')) {
            if ($status === 'habis') {
                $query->where('jumlah', 0);
            }

            if ($status === 'menipis') {
                $query->whereColumn('jumlah', '<=', 'min_stok')->where('jumlah', '>', 0);
            }

            if ($status === 'aman') {
                $query->whereColumn('jumlah', '>', 'min_stok');
            }
        }

        // Sorting berbasis parameter query string.
        $sort = request('sort', 'newest');
        if ($sort === 'stok_asc') {
            $query->orderBy('jumlah');
        } elseif ($sort === 'stok_desc') {
            $query->orderByDesc('jumlah');
        } elseif ($sort === 'nama_asc') {
            $query->orderBy('nama');
        } elseif ($sort === 'nama_desc') {
            $query->orderByDesc('nama');
        } else {
            $query->latest();
        }

        // withQueryString menjaga parameter filter tetap ada saat pindah halaman pagination.
        $items = $query->paginate(10)->withQueryString();
        // Dipakai untuk isi dropdown filter kategori secara dinamis.
        $kategoriList = Item::query()->select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        return view('items.index', compact('items', 'kategoriList'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi data barang sebelum disimpan.
        $data = $request->validate([
            'kode_barang' => ['required', 'string', 'max:100', 'unique:items,kode_barang'],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'min_stok' => ['required', 'integer', 'min:0', 'max:100000'],
            'kondisi' => ['required', 'in:baik,rusak ringan,rusak berat'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        // Simpan siapa user yang membuat data barang.
        $data['user_id'] = Auth::id();

        // Upload foto ke storage publik bila file tersedia.
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil ditambahkan.');
    }

    public function show(Item $item)
    {
        // Muat relasi mutasi stok terbaru untuk halaman detail barang.
        $item->load(['movements' => function ($query) {
            $query->with('user')->latest()->take(12);
        }]);

        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        // Validasi update; kode barang tetap harus unik selain milik item ini.
        $data = $request->validate([
            'kode_barang' => ['required', 'string', 'max:100', 'unique:items,kode_barang,' . $item->id],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'min_stok' => ['required', 'integer', 'min:0', 'max:100000'],
            'kondisi' => ['required', 'in:baik,rusak ringan,rusak berat'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        // Jika ada foto baru, hapus foto lama untuk menghindari file yatim.
        if ($request->hasFile('foto')) {
            if ($item->foto) {
                Storage::disk('public')->delete($item->foto);
            }
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        // Hapus file foto dari storage sebelum record barang dihapus.
        if ($item->foto) {
            Storage::disk('public')->delete($item->foto);
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Data barang berhasil dihapus.');
    }

    public function adjustStock(Request $request, Item $item): RedirectResponse
    {
        // Validasi data mutasi stok dari form detail barang.
        $data = $request->validate([
            'tipe' => ['required', 'in:masuk,keluar'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:100000'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        // Transaksi DB menjaga perubahan stok dan histori mutasi tetap konsisten.
        DB::transaction(function () use ($data, $item) {
            $item->refresh();

            // Cegah stok minus saat mutasi tipe keluar.
            if ($data['tipe'] === 'keluar' && $item->jumlah < $data['jumlah']) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah stok keluar melebihi stok yang tersedia.',
                ]);
            }

            // Hitung stok baru berdasarkan tipe mutasi.
            $item->jumlah = $data['tipe'] === 'masuk'
                ? $item->jumlah + $data['jumlah']
                : $item->jumlah - $data['jumlah'];
            $item->save();

            // Simpan log mutasi untuk audit histori pergerakan stok.
            InventoryMovement::create([
                'item_id' => $item->id,
                'user_id' => Auth::id(),
                'tipe' => $data['tipe'],
                'jumlah' => $data['jumlah'],
                'catatan' => $data['catatan'] ?? null,
            ]);
        });

        return back()->with('success', 'Mutasi stok berhasil disimpan.');
    }

    public function borrow(Request $request, Item $item): RedirectResponse
    {
        $data = $request->validate([
            'nama_peminjam' => ['required', 'string', 'max:100'],
        ]);

        if ($item->is_dipinjam) {
            return back()->withErrors([
                'nama_peminjam' => 'Barang ini masih berstatus dipinjam.',
            ]);
        }

        $item->update([
            'is_dipinjam' => true,
            'nama_peminjam' => $data['nama_peminjam'],
            'tanggal_pinjam' => now(),
        ]);

        return back()->with('success', 'Status barang diubah menjadi sedang dipinjam.');
    }

    public function returnItem(Item $item): RedirectResponse
    {
        if (! $item->is_dipinjam) {
            return back()->withErrors([
                'nama_peminjam' => 'Barang ini sudah berstatus tersedia.',
            ]);
        }

        $item->update([
            'is_dipinjam' => false,
            'nama_peminjam' => null,
            'tanggal_pinjam' => null,
        ]);

        return back()->with('success', 'Barang berhasil dikembalikan dan status menjadi tersedia.');
    }
}
