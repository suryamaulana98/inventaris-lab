@extends('layouts.app')

@section('title', 'Detail Barang - Inventaris Lab')

@section('content')
    {{-- Header detail barang dengan shortcut edit dan kembali. --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="section-title mb-1">Detail Barang</h3>
            <div class="text-muted-soft">Informasi lengkap barang dan histori mutasi stok.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
            <a href="{{ route('items.index') }}" class="btn btn-soft btn-sm">Kembali</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="app-panel p-3 p-md-4 h-100">
                @if ($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="foto {{ $item->nama }}"
                        class="w-100 rounded-4 border object-fit-cover mb-3" style="height: 240px;">
                @endif

                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="mb-1">{{ $item->nama }}</h5>
                        <div class="text-muted-soft small">{{ $item->kode_barang }}</div>
                    </div>
                    {{-- Status stok ditentukan dari perbandingan jumlah terhadap min_stok. --}}
                    @php
                        $statusClass = 'badge-soft-green';
                        $statusLabel = 'Aman';

                        if ($item->jumlah === 0) {
                            $statusClass = 'badge-soft-red';
                            $statusLabel = 'Habis';
                        } elseif ($item->jumlah <= $item->min_stok) {
                            $statusClass = 'badge-soft-orange';
                            $statusLabel = 'Menipis';
                        }
                    @endphp
                    <span class="badge badge-soft {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>

                <div class="small text-muted-soft mb-2">Kategori: <span class="text-dark">{{ $item->kategori }}</span></div>
                <div class="small text-muted-soft mb-2">Lokasi: <span class="text-dark">{{ $item->lokasi }}</span></div>
                <div class="small text-muted-soft mb-2">Kondisi: <span class="text-dark">{{ ucfirst($item->kondisi) }}</span></div>
                <div class="small text-muted-soft mb-2">Stok saat ini: <span class="text-dark fw-semibold">{{ $item->jumlah }} unit</span></div>
                <div class="small text-muted-soft mb-3">Minimum stok: <span class="text-dark">{{ $item->min_stok }} unit</span></div>

                <div class="small text-muted-soft mb-2">Status peminjaman:</div>
                @if ($item->is_dipinjam)
                    <span class="badge badge-soft badge-soft-red mb-2">Sedang Dipinjam</span>
                    <div class="small text-muted-soft">Peminjam: {{ $item->nama_peminjam }}</div>
                    <div class="small text-muted-soft mb-3">Tanggal pinjam: {{ optional($item->tanggal_pinjam)->format('d M Y H:i') }}</div>
                @else
                    <span class="badge badge-soft badge-soft-green mb-3">Tersedia</span>
                @endif

                <div class="small text-muted-soft">Deskripsi</div>
                <div>{{ $item->deskripsi ?: '-' }}</div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="app-panel p-3 p-md-4 mb-4">
                <h5 class="section-title mb-3">Status Peminjaman Barang</h5>

                @if (! $item->is_dipinjam)
                    <form action="{{ route('items.borrow', $item->id) }}" method="POST" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-8">
                            <label class="form-label">Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" class="form-control"
                                placeholder="Contoh: Andi Pratama" required>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-glow btn-sm w-100">Set Barang Dipinjam</button>
                        </div>
                    </form>
                @else
                    <div class="small text-muted-soft mb-3">
                        Barang sedang dipinjam oleh <span class="text-dark fw-semibold">{{ $item->nama_peminjam }}</span>.
                    </div>
                    <form action="{{ route('items.return', $item->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-soft btn-sm border-success text-success">Set Barang Dikembalikan</button>
                    </form>
                @endif
            </div>

            {{-- Form mutasi stok cepat untuk mencatat stok masuk/keluar. --}}
            <div class="app-panel p-3 p-md-4 mb-4">
                <h5 class="section-title mb-3">Catat Mutasi Stok</h5>
                <form action="{{ route('items.adjust-stock', $item->id) }}" method="POST" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label">Tipe Mutasi</label>
                        <select name="tipe" class="form-select" required>
                            <option value="masuk">Stok Masuk</option>
                            <option value="keluar">Stok Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" min="1" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control" placeholder="Contoh: Dipakai praktikum kelas B">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-glow btn-sm">Simpan Mutasi</button>
                    </div>
                </form>
            </div>

            {{-- Tabel histori mutasi stok sebagai jejak audit. --}}
            <div class="app-panel p-3 p-md-4">
                <h5 class="section-title mb-3">Riwayat Mutasi</h5>
                <div class="table-responsive">
                    <table class="table table-modern table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($item->movements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-soft {{ $movement->tipe === 'masuk' ? 'badge-soft-green' : 'badge-soft-red' }}">
                                            {{ ucfirst($movement->tipe) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->jumlah }}</td>
                                    <td>{{ $movement->catatan ?: '-' }}</td>
                                    <td>{{ $movement->user->name ?? 'Sistem' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted-soft py-3">Belum ada mutasi stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
