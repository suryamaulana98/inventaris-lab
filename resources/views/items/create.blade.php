@extends('layouts.app')

@section('title', 'Tambah Barang - Inventaris Lab')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="section-title mb-1">Tambah Barang</h3>
            <div class="text-muted-soft">Lengkapi detail barang untuk dimasukkan ke inventaris.</div>
        </div>
        <a href="{{ route('items.index') }}" class="btn btn-soft btn-sm">Kembali</a>
    </div>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="app-panel p-3 p-md-4">
        @csrf

        <div class="row g-3">
            <div class="col-md-6 mb-3">
                <label class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" class="form-control"
                    placeholder="Contoh: LAB-MIK-001" required>
                <div class="form-text">Kode unik inventaris.</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control"
                    placeholder="Contoh: Mikroskop Digital" required>
                <div class="form-text">Nama aset sesuai label barang.</div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4 mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori') }}" class="form-control"
                    placeholder="Contoh: Alat Praktikum" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="form-control"
                    placeholder="Contoh: Rak A - Lab Biologi" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jumlah" min="0" value="{{ old('jumlah', 0) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Minimum Stok</label>
                <input type="number" name="min_stok" min="0" value="{{ old('min_stok', 5) }}" class="form-control" required>
                <div class="form-text">Batas warning stok menipis untuk barang ini.</div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6 mb-3">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-select" required>
                    <option value="baik" @selected(old('kondisi') == 'baik')>Baik</option>
                    <option value="rusak ringan" @selected(old('kondisi') == 'rusak ringan')>Rusak Ringan</option>
                    <option value="rusak berat" @selected(old('kondisi') == 'rusak berat')>Rusak Berat</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Foto (opsional)</label>
                <input type="file" name="foto" class="form-control">
                <div class="form-text">JPG/PNG maksimal 2MB.</div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi (opsional)</label>
            <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-glow">Simpan Barang</button>
            <a href="{{ route('items.index') }}" class="btn btn-soft">Batal</a>
        </div>
    </form>
@endsection
