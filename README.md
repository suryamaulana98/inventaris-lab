# Sistem Inventaris Laboratoriumm

Aplikasi web untuk mengelola data inventaris laboratorium berbasis Laravel. Project ini menyediakan manajemen barang, dashboard ringkasan, mutasi stok masuk/keluar, serta status peminjaman barang. dan pemakaian

## Fitur Utama

- Autentikasi pengguna (login, register, logout).
- Dashboard ringkasan inventaris.
- CRUD data barang inventaris.
- Upload foto barang.
- Filter, pencarian, dan sorting data barang.
- Pencatatan mutasi stok (masuk/keluar) per barang.
- Status peminjaman barang (tersedia atau sedang dipinjam).
- Informasi peminjam aktif dan waktu pinjam.

## Teknologi yang Digunakan

- Laravel 13
- PHP 8.4
- MySQL
- Blade Template
- Bootstrap 5

## Struktur Modul

1. Autentikasi

- Halaman login dan register.
- Middleware guest dan auth untuk proteksi route.

2. Data Barang

- Menambah, mengubah, menghapus, dan melihat detail barang.
- Menyimpan informasi: kode barang, nama, kategori, lokasi, stok, minimum stok, kondisi, foto, deskripsi.

3. Mutasi Stok

- Mencatat stok masuk atau stok keluar.
- Otomatis memperbarui jumlah stok saat mutasi disimpan.
- Menyimpan histori mutasi untuk audit.

4. Peminjaman Barang

- Set barang menjadi sedang dipinjam.
- Menyimpan nama peminjam dan tanggal pinjam.
- Set barang dikembalikan agar status kembali tersedia.

5. Dashboard

- Menampilkan KPI penting: total barang, total unit, stok menipis, stok habis, barang rusak, barang dipinjam.
- Menampilkan daftar peringatan inventaris.
- Menampilkan aktivitas mutasi terbaru.

## Instalasi dan Menjalankan Project

1. Clone repository

```bash
git clone https://github.com/suryamaulana98/inventaris-lab.git
cd inventaris-lab
```

2. Install dependency backend

```bash
composer install
```

3. Siapkan file environment

```bash
cp .env.example .env
```

Jika menggunakan Windows PowerShell dan `cp` tidak tersedia:

```powershell
Copy-Item .env.example .env
```

4. Generate APP_KEY

```bash
php artisan key:generate
```

5. Atur koneksi database pada file `.env`

Contoh:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris
DB_USERNAME=root
DB_PASSWORD=
```

6. Jalankan migrasi database

```bash
php artisan migrate
```

7. Install dependency frontend

```bash
npm install
```

8. Jalankan server aplikasi

```bash
php artisan serve
```

9. Jalankan Vite (opsional, jika mengubah asset frontend)

```bash
npm run dev
```

## Akun dan Akses

- Buat akun dari halaman register.
- Login menggunakan akun yang sudah dibuat.
- Setelah login, pengguna akan diarahkan ke dashboard.

## Alur Singkat Peminjaman Barang

1. Buka menu Data Barang.
2. Pilih Detail pada barang yang ingin dipinjam.
3. Isi nama peminjam lalu klik Set Barang Dipinjam.
4. Saat barang kembali, klik Set Barang Dikembalikan.

## Menjalankan Pengujian

```bash
php artisan test
```

## Catatan Pengembangan

- Session telah dikonfigurasi agar stabil untuk lingkungan lokal.
- Pastikan migrasi terbaru sudah dijalankan sebelum menguji fitur dashboard dan peminjaman.

## Lisensi

Project ini menggunakan lisensi MIT.
