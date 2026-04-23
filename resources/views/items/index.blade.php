@extends('layouts.app')

@section('title', 'Data Barang - Inventaris Lab')

@section('content')
    {{-- Judul halaman dan tombol tambah data barang. --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="section-title mb-1">Data Barang</h3>
            <div class="text-muted-soft">Kelola semua aset dengan filter pencarian dan status stok.</div>
        </div>
        <a href="{{ route('items.create') }}" class="btn btn-glow"><i class="bi bi-plus-lg"></i> Tambah Barang</a>
    </div>

    {{-- Form filter data untuk search, kondisi, status stok, dan sorting. --}}
    <div class="app-panel p-3 p-md-4 mb-4">
        <form method="GET" action="{{ route('items.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Kode, nama, kategori, lokasi...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach ($kategoriList as $kategori)
                        <option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>{{ $kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-select">
                    <option value="">Semua</option>
                    <option value="baik" @selected(request('kondisi') === 'baik')>Baik</option>
                    <option value="rusak ringan" @selected(request('kondisi') === 'rusak ringan')>Rusak Ringan</option>
                    <option value="rusak berat" @selected(request('kondisi') === 'rusak berat')>Rusak Berat</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status Stok</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="aman" @selected(request('status') === 'aman')>Aman</option>
                    <option value="menipis" @selected(request('status') === 'menipis')>Menipis</option>
                    <option value="habis" @selected(request('status') === 'habis')>Habis</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status Pinjam</label>
                <select name="status_pinjam" class="form-select">
                    <option value="">Semua</option>
                    <option value="tersedia" @selected(request('status_pinjam') === 'tersedia')>Tersedia</option>
                    <option value="dipinjam" @selected(request('status_pinjam') === 'dipinjam')>Dipinjam</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Urutkan</label>
                <select name="sort" class="form-select">
                    <option value="newest" @selected(request('sort', 'newest') === 'newest')>Terbaru</option>
                    <option value="stok_asc" @selected(request('sort') === 'stok_asc')>Stok Terendah</option>
                    <option value="stok_desc" @selected(request('sort') === 'stok_desc')>Stok Tertinggi</option>
                    <option value="nama_asc" @selected(request('sort') === 'nama_asc')>Nama A-Z</option>
                    <option value="nama_desc" @selected(request('sort') === 'nama_desc')>Nama Z-A</option>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-glow btn-sm">Terapkan Filter</button>
                <a href="{{ route('items.index') }}" class="btn btn-soft btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="app-panel p-2 p-md-3">
        <div class="table-responsive">
            <table class="table table-modern table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Stok</th>
                        <th>Status Pinjam</th>
                        <th>Kondisi</th>
                        <th>Foto</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        {{-- Badge status stok dihitung per barang memakai min_stok masing-masing. --}}
                        @php
                            $stokClass = 'badge-soft-green';
                            $stokLabel = 'Aman';

                            if ($item->jumlah === 0) {
                                $stokClass = 'badge-soft-red';
                                $stokLabel = 'Habis';
                            } elseif ($item->jumlah <= $item->min_stok) {
                                $stokClass = 'badge-soft-orange';
                                $stokLabel = 'Menipis';
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->nama }}</div>
                                <div class="small text-muted-soft">{{ $item->kode_barang }}</div>
                            </td>
                            <td>{{ $item->kategori }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>
                                <div>{{ $item->jumlah }} unit</div>
                                <div class="small text-muted-soft">Min: {{ $item->min_stok }}</div>
                                <span class="badge badge-soft {{ $stokClass }}">{{ $stokLabel }}</span>
                            </td>
                            <td>
                                @if ($item->is_dipinjam)
                                    <span class="badge badge-soft badge-soft-red">Dipinjam</span>
                                    <div class="small text-muted-soft">oleh {{ $item->nama_peminjam }}</div>
                                @else
                                    <span class="badge badge-soft badge-soft-green">Tersedia</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($item->kondisi) }}</td>
                            <td>
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="foto" width="64" height="50"
                                        class="rounded-3 object-fit-cover border">
                                @else
                                    <span class="text-muted-soft small">Tidak ada</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1 flex-wrap">
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-soft btn-sm">Detail</a>
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted-soft py-3">Belum ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $items->links() }}</div>
@endsection
