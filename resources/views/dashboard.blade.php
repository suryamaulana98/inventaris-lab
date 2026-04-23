@extends('layouts.app')

@section('title', 'Dashboard - Inventaris Lab')

@section('content')
    {{-- Header dashboard dan tombol aksi cepat. --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="section-title mb-1">Dashboard Inventaris</h3>
            <div class="text-muted-soft">Ringkasan kondisi stok, kualitas barang, dan aktivitas mutasi terbaru.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('items.create') }}" class="btn btn-glow btn-sm"><i class="bi bi-plus-lg"></i> Tambah Barang</a>
            <a href="{{ route('items.index') }}" class="btn btn-soft btn-sm">Kelola Inventaris</a>
        </div>
    </div>

    {{-- Ringkasan status operasional harian. --}}
    <div class="app-panel p-4 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-lg-8">
                <div class="fw-semibold mb-1">Status Operasional Inventaris</div>
                <div class="text-muted-soft small">
                    Fokus utama hari ini: tindak lanjut item stok habis/menipis dan perhatikan barang dengan kondisi rusak.
                    Sistem warning menggunakan ambang minimum stok per barang.
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="badge badge-soft badge-soft-blue me-1">Mutasi Hari Ini: {{ $totalMutasiHariIni }}</span>
                <span class="badge badge-soft badge-soft-orange">Risiko Stok: {{ $persentaseRisikoStok }}%</span>
            </div>
        </div>
    </div>

    {{-- KPI utama inventaris untuk monitoring cepat. --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="app-panel p-3 h-100">
                <div class="text-muted-soft small">Total Jenis Barang</div>
                <div class="fs-3 fw-bold">{{ $totalBarang }}</div>
                <div class="small text-muted-soft">Aset terdaftar</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="app-panel p-3 h-100">
                <div class="text-muted-soft small">Total Unit</div>
                <div class="fs-3 fw-bold text-primary">{{ $totalUnit }}</div>
                <div class="small text-muted-soft">Unit tersedia saat ini</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="app-panel p-3 h-100">
                <div class="text-muted-soft small">Stok Menipis</div>
                <div class="fs-3 fw-bold text-warning">{{ $stokMenipis }}</div>
                <div class="small text-muted-soft">Di bawah/di ambang minimum</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="app-panel p-3 h-100">
                <div class="text-muted-soft small">Sedang Dipinjam</div>
                <div class="fs-3 fw-bold text-info">{{ $barangDipinjam }}</div>
                <div class="small text-muted-soft">Barang belum dikembalikan</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="app-panel p-3 h-100">
                <div class="text-muted-soft small">Barang Rusak</div>
                <div class="fs-3 fw-bold text-danger">{{ $barangRusak }}</div>
                <div class="small text-muted-soft">Baik: {{ $barangBaik }} item</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="app-panel p-3 p-md-4 h-100">
                <h5 class="section-title mb-3">Peringatan Inventaris</h5>
                <div class="table-responsive">
                    <table class="table table-modern table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($warningItems as $item)
                                {{-- Menentukan label status stok berdasarkan jumlah aktual. --}}
                                @php
                                    $isOut = $item->jumlah === 0;
                                    $statusLabel = $isOut ? 'Habis' : ($item->jumlah <= 2 ? 'Kritis' : 'Menipis');
                                    $statusClass = $isOut ? 'badge-soft-red' : 'badge-soft-orange';
                                @endphp
                                <tr>
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->jumlah }} / min {{ $item->min_stok }}</td>
                                    <td><span class="badge badge-soft {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-soft btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted-soft py-3">Tidak ada item warning saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="app-panel p-3 p-md-4 h-100">
                <h5 class="section-title mb-3">Aktivitas Mutasi Terbaru</h5>
                <div class="d-flex flex-column gap-3">
                    @forelse ($recentMovements as $movement)
                        {{-- Timeline mutasi stok terbaru beserta pelaku dan catatan. --}}
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold small">
                                    {{ $movement->item->nama ?? 'Barang tidak ditemukan' }}
                                </div>
                                <div class="small text-muted-soft">
                                    {{ $movement->tipe === 'masuk' ? 'Stok Masuk' : 'Stok Keluar' }}
                                    • {{ $movement->jumlah }} unit
                                    • {{ $movement->user->name ?? 'Sistem' }}
                                </div>
                                @if ($movement->catatan)
                                    <div class="small text-muted-soft">Catatan: {{ $movement->catatan }}</div>
                                @endif
                            </div>
                            <span class="badge badge-soft {{ $movement->tipe === 'masuk' ? 'badge-soft-green' : 'badge-soft-red' }}">
                                {{ $movement->tipe === 'masuk' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-muted-soft small">Belum ada aktivitas mutasi stok.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="app-panel p-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="small text-muted-soft">Stok Habis</div>
                <div class="fs-2 fw-bold text-danger">{{ $stokHabis }}</div>
            </div>
            <div class="col-md-8">
                <div class="small text-muted-soft">
                    Prioritas: lakukan pengadaan untuk stok habis, lakukan restock item menipis, dan jadwalkan inspeksi pada barang rusak.
                </div>
            </div>
        </div>
    </div>
@endsection
