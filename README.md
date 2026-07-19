<p align="center">
    <img src="public/backend/assets/img/logo.png" width="130" alt="Logo Dashboard Monitoring">
</p>

<h1 align="center">Dashboard Monitoring Perusahaan Jasa</h1>

<p align="center">
    Sistem informasi berbasis web untuk memonitor kinerja karyawan, absensi, transaksi jasa, dan kepuasan pelanggan.
</p>

<p align="center">
    <a href="https://github.com/USERNAME/NAMA-REPOSITORY">
        <img src="https://img.shields.io/github/stars/USERNAME/NAMA-REPOSITORY?style=flat-square" alt="GitHub Stars">
    </a>
    <a href="https://github.com/USERNAME/NAMA-REPOSITORY/issues">
        <img src="https://img.shields.io/github/issues/USERNAME/NAMA-REPOSITORY?style=flat-square" alt="GitHub Issues">
    </a>
    <a href="https://github.com/USERNAME/NAMA-REPOSITORY/blob/main/LICENSE">
        <img src="https://img.shields.io/github/license/USERNAME/NAMA-REPOSITORY?style=flat-square" alt="License">
    </a>
    <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel" alt="Laravel">
    <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/PostgreSQL-Database-4169E1?style=flat-square&logo=postgresql" alt="PostgreSQL">
</p>

---

## Tentang Aplikasi

**Dashboard Monitoring Perusahaan Jasa** adalah aplikasi berbasis web yang dikembangkan untuk membantu perusahaan dalam memonitor aktivitas operasional secara terintegrasi.

Aplikasi ini mencakup pengelolaan karyawan, absensi, cuti, target kinerja, transaksi jasa, pembayaran, pengeluaran, serta kepuasan pelanggan. Informasi utama ditampilkan melalui dashboard yang mudah dipahami sehingga dapat membantu manajemen dalam mengambil keputusan.

## Fitur Utama

### Autentikasi dan Hak Akses

- Login dan logout pengguna
- Manajemen pengguna
- Role dan permission
- Pembatasan menu berdasarkan hak akses
- Manajemen sesi pengguna

### Manajemen Karyawan

- Data karyawan
- Data departemen
- Data jabatan
- Jadwal kerja
- Penempatan jadwal karyawan
- Status kepegawaian

### Absensi dan Cuti

- Pencatatan jam masuk dan jam pulang
- Perhitungan keterlambatan
- Perhitungan lembur
- Status kehadiran
- Pengajuan cuti
- Persetujuan atau penolakan cuti

### Produktivitas Karyawan

- Periode penilaian
- Indikator kinerja atau KPI
- Target karyawan
- Aktivitas pekerjaan
- Realisasi pekerjaan
- Penilaian kinerja
- Persentase pencapaian target
- Peringkat karyawan

### Pelanggan dan Layanan

- Data pelanggan
- Kategori jasa
- Data layanan
- Harga layanan
- Status layanan

### Transaksi Jasa

- Pembuatan pesanan jasa
- Detail item pesanan
- Riwayat status pesanan
- Penugasan pekerjaan
- Invoice pelanggan
- Pembayaran
- Pengeluaran transaksi

### Kepuasan Pelanggan

- Penilaian kualitas pelayanan
- Penilaian kinerja karyawan
- Penilaian ketepatan waktu
- Penilaian harga
- Komentar pelanggan
- Keluhan pelanggan
- Status penyelesaian keluhan

### Dashboard Eksekutif

- Statistik jumlah karyawan
- Rekapitulasi absensi
- Rekapitulasi pengajuan cuti
- Produktivitas karyawan
- Pencapaian target
- Jumlah pelanggan
- Jumlah transaksi jasa
- Pendapatan perusahaan
- Pengeluaran perusahaan
- Invoice belum dibayar
- Tingkat kepuasan pelanggan
- Jumlah keluhan pelanggan

### Fitur Pendukung

- Notifikasi pengguna
- Audit log aktivitas
- Pengaturan sistem
- Pencarian dan filter data
- Pagination
- Soft delete
- Tampilan responsif

## Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| Laravel | Framework backend |
| PHP | Bahasa pemrograman backend |
| PostgreSQL | Sistem manajemen database |
| Blade | Template engine Laravel |
| Bootstrap | Framework antarmuka |
| JavaScript | Interaksi halaman |
| Chart.js | Visualisasi grafik dashboard |
| HTML5 dan CSS3 | Struktur dan desain halaman |
| Git dan GitHub | Version control |

## Struktur Modul Database

Database aplikasi dikelompokkan menjadi beberapa modul utama:

1. Autentikasi dan akses
2. Role dan permission
3. Organisasi dan karyawan
4. Absensi dan cuti
5. Produktivitas karyawan
6. Pelanggan dan jasa
7. Transaksi jasa
8. Kepuasan pelanggan
9. Pendukung dashboard

Beberapa tabel utama yang digunakan:

```text
users
roles
permissions
departments
positions
employees
work_schedules
employee_schedules
attendances
leave_requests
performance_periods
performance_indicators
employee_targets
employee_activities
employee_performances
customers
service_categories
services
service_orders
service_order_items
service_order_status_histories
invoices
payments
expenses
customer_feedback
customer_complaints
notifications
audit_logs
system_settings