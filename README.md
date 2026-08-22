# Dashboard Eksekutif

Sistem informasi berbasis web untuk memantau produktivitas karyawan, absensi, transaksi jasa, keuangan, dan kepuasan pelanggan.

![Preview Dashboard](public/backend/assets/img/readme/dashboard-preview.png)

## Fitur Utama

- Login dan hak akses berdasarkan role
- Manajemen karyawan, departemen, jabatan, dan jadwal
- Absensi, keterlambatan, lembur, dan pengajuan cuti
- KPI, target, aktivitas, dan penilaian karyawan
- Data pelanggan, layanan, pesanan, dan penugasan
- Invoice, pembayaran, pengeluaran, dan laporan keuangan
- Feedback dan keluhan pelanggan
- Dashboard statistik dan grafik monitoring

## Teknologi

- Laravel 13
- PHP 8.2+
- PostgreSQL
- Blade dan Bootstrap 5
- Vite dan JavaScript
- Chart.js

## Instalasi

Pastikan PHP, Composer, Node.js, NPM, dan PostgreSQL sudah tersedia.

```bash
# Clone repository
git clone https://github.com/Rahmyall/dashboard-eksekutif.git
cd dashboard-eksekutif

# Instal dependensi
composer install
npm install

# Siapkan environment
cp .env.example .env
php artisan key:generate

# Atur koneksi database di file .env, kemudian jalankan migrasi
php artisan migrate --seed
php artisan storage:link
```

Untuk Windows Command Prompt, gunakan `copy .env.example .env` sebagai pengganti `cp .env.example .env`.

## Menjalankan Aplikasi

Jalankan dua perintah berikut pada terminal terpisah:

```bash
php artisan serve
npm run dev
```

Aplikasi tersedia di [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Perintah Pengembangan

```bash
php artisan test          # Menjalankan pengujian
php artisan route:list    # Melihat daftar route
php artisan optimize:clear # Membersihkan cache
npm run build             # Membuat aset produksi
```

## Struktur Utama

```text
app/         Logika aplikasi Laravel
database/    Migrasi, factory, dan seeder
 resources/  View dan aset frontend
 routes/     Route web dan API
 public/     Aset publik
 tests/      Pengujian aplikasi
```

## Catatan

Jangan mengunggah file `.env`, password database, API key, atau data sensitif ke repository.
