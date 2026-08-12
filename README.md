<a id="readme-top"></a>

<div align="center">
  <img
    src="public/backend/assets/img/logo.png"
    alt="Logo Dashboard Monitoring Produktivitas Karyawan dan Transaksi Jasa"
    width="120"
  />

  <h1>Dashboard Monitoring Produktivitas Karyawan<br>dan Transaksi Jasa</h1>

  <p>
    Sistem informasi berbasis web untuk memonitor produktivitas karyawan,
    absensi, transaksi jasa, keuangan operasional, dan kepuasan pelanggan
    dalam satu dashboard terintegrasi.
  </p>

  <p>
    <img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white" alt="Laravel 13" />
    <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white" alt="PHP 8.2 atau lebih baru" />
    <img src="https://img.shields.io/badge/PostgreSQL-Database-4169E1?logo=postgresql&logoColor=white" alt="PostgreSQL" />
    <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white" alt="Bootstrap 5" />
    <img src="https://img.shields.io/badge/Status-Development-orange" alt="Status Development" />
  </p>

  <p>
    <a href="#tentang-aplikasi">Tentang</a> •
    <a href="#fitur-utama">Fitur</a> •
    <a href="#tampilan-aplikasi">Tampilan</a> •
    <a href="#teknologi">Teknologi</a> •
    <a href="#instalasi">Instalasi</a> •
    <a href="#struktur-database">Database</a> •
    <a href="#kontribusi">Kontribusi</a>
  </p>
</div>

<div align="center">
  <img
    src="public/backend/assets/img/readme/dashboard-preview.png"
    alt="Preview Dashboard Monitoring Produktivitas Karyawan dan Transaksi Jasa"
    width="100%"
  />
  <br>
  <sub>Dashboard eksekutif untuk memantau kinerja karyawan, transaksi, keuangan, dan aktivitas operasional perusahaan.</sub>
</div>

## Tentang Aplikasi

Dashboard Monitoring Produktivitas Karyawan dan Transaksi Jasa dikembangkan untuk membantu perusahaan jasa mencatat, menghubungkan, dan memantau aktivitas operasional secara terpusat.

Informasi penting disajikan melalui dashboard interaktif agar manajemen dapat melakukan pemantauan dan pengambilan keputusan berdasarkan data yang tersedia.

## Cakupan Sistem

| Area | Cakupan |
| --- | --- |
| Sumber daya manusia | Karyawan, departemen, jabatan, jadwal kerja, absensi, dan cuti |
| Produktivitas | KPI, target, aktivitas, realisasi, penilaian, dan peringkat karyawan |
| Pelanggan dan layanan | Pelanggan, kategori jasa, layanan, harga, dan riwayat pelanggan |
| Operasional jasa | Pesanan, item layanan, penugasan, progres pekerjaan, dan riwayat status |
| Keuangan | Invoice, pembayaran, pendapatan, pengeluaran, dan tagihan belum dibayar |
| Kepuasan pelanggan | Penilaian layanan, komentar, keluhan, tindak lanjut, dan status penyelesaian |
| Monitoring | Statistik, rekapitulasi, grafik, notifikasi, audit log, dan laporan |

<p align="right"><a href="#readme-top">Kembali ke atas</a></p>

## Fitur Utama

| No. | Modul | Fungsi Utama |
| --- | --- | --- |
| 1 | Autentikasi dan Hak Akses | Login, akun pengguna, role, permission, sesi, dan pembatasan menu |
| 2 | Manajemen Karyawan | Karyawan, departemen, jabatan, jadwal, dan status kepegawaian |
| 3 | Absensi dan Cuti | Jam masuk, jam pulang, keterlambatan, lembur, pengajuan, dan persetujuan cuti |
| 4 | Produktivitas Karyawan | KPI, target, aktivitas, realisasi, penilaian, pencapaian, dan peringkat |
| 5 | Pelanggan dan Layanan | Data pelanggan, kategori jasa, layanan, harga, dan status layanan |
| 6 | Transaksi Jasa | Pesanan, item pesanan, penugasan, invoice, pembayaran, pengeluaran, dan progres |
| 7 | Kepuasan Pelanggan | Penilaian, komentar, keluhan, tindak lanjut, dan penyelesaian keluhan |
| 8 | Dashboard Eksekutif | Statistik karyawan, absensi, transaksi, keuangan, kepuasan, dan grafik perkembangan |
| 9 | Fitur Pendukung | Notifikasi, audit log, pencarian, filter, pagination, ekspor, dan soft delete |

<details>
<summary><strong>Lihat rincian lengkap setiap modul</strong></summary>

### 1. Autentikasi dan Hak Akses

- Login dan logout pengguna
- Manajemen akun pengguna
- Manajemen role dan permission
- Pembatasan menu berdasarkan hak akses
- Manajemen sesi pengguna
- Perubahan kata sandi
- Status aktif dan nonaktif pengguna

### 2. Manajemen Karyawan

- Data karyawan
- Data departemen
- Data jabatan
- Jadwal kerja
- Penempatan jadwal karyawan
- Status kepegawaian
- Riwayat data karyawan

### 3. Absensi dan Cuti

- Pencatatan jam masuk dan jam pulang
- Perhitungan keterlambatan dan lembur
- Status kehadiran
- Pengajuan, persetujuan, dan penolakan cuti
- Riwayat pengajuan cuti

### 4. Produktivitas Karyawan

- Periode penilaian
- Indikator kinerja atau KPI
- Target dan aktivitas karyawan
- Realisasi pekerjaan
- Penilaian kinerja
- Persentase pencapaian target
- Peringkat dan riwayat hasil penilaian

### 5. Pelanggan dan Layanan

- Data dan riwayat pelanggan
- Kategori jasa
- Data layanan
- Harga dan status layanan

### 6. Transaksi Jasa

- Pembuatan pesanan jasa
- Detail item pesanan
- Riwayat status pesanan
- Penugasan dan monitoring progres pekerjaan
- Invoice pelanggan
- Pencatatan pembayaran dan pengeluaran
- Riwayat transaksi

### 7. Kepuasan Pelanggan

- Penilaian kualitas pelayanan
- Penilaian kinerja karyawan
- Penilaian ketepatan waktu dan harga
- Komentar pelanggan
- Keluhan dan tindak lanjut
- Status penyelesaian keluhan

### 8. Dashboard Eksekutif

- Statistik jumlah karyawan
- Rekapitulasi absensi dan cuti
- Produktivitas dan pencapaian target
- Jumlah pelanggan dan transaksi jasa
- Total pendapatan dan pengeluaran
- Invoice belum dibayar
- Tingkat kepuasan dan jumlah keluhan
- Grafik perkembangan transaksi
- Grafik pendapatan dan pengeluaran

### 9. Fitur Pendukung

- Notifikasi pengguna
- Audit log aktivitas
- Pengaturan sistem
- Pencarian, filter, dan pengurutan data
- Pagination
- Soft delete dan restore data
- Ekspor laporan
- Tampilan responsif
- Validasi formulir dan konfirmasi tindakan

</details>

<p align="right"><a href="#readme-top">Kembali ke atas</a></p>

## Tampilan Aplikasi

<table>
  <tr>
    <td width="50%" align="center">
      <img src="public/backend/assets/img/readme/login-page.png" alt="Halaman Login" width="100%" />
      <br>
      <strong>Halaman Login</strong>
      <br>
      <sub>Akses sistem berdasarkan akun, peran, dan hak akses pengguna.</sub>
    </td>
    <td width="50%" align="center">
      <img src="public/backend/assets/img/readme/dashboard-eksekutif.png" alt="Dashboard Eksekutif" width="100%" />
      <br>
      <strong>Dashboard Eksekutif</strong>
      <br>
      <sub>Ringkasan indikator utama operasional perusahaan.</sub>
    </td>
  </tr>
  <tr>
    <td width="50%" align="center">
      <img src="public/backend/assets/img/readme/data-karyawan.png" alt="Manajemen Karyawan" width="100%" />
      <br>
      <strong>Manajemen Karyawan</strong>
      <br>
      <sub>Pengelolaan data karyawan, organisasi, dan jadwal kerja.</sub>
    </td>
    <td width="50%" align="center">
      <img src="public/backend/assets/img/readme/transaksi-jasa.png" alt="Transaksi Jasa" width="100%" />
      <br>
      <strong>Transaksi Jasa</strong>
      <br>
      <sub>Pengelolaan pesanan, penugasan, invoice, dan pembayaran.</sub>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <img src="public/backend/assets/img/readme/kepuasan-pelanggan.png" alt="Kepuasan Pelanggan" width="75%" />
      <br>
      <strong>Kepuasan Pelanggan</strong>
      <br>
      <sub>Pencatatan penilaian layanan, komentar, keluhan, dan tindak lanjut.</sub>
    </td>
  </tr>
</table>

> [!NOTE]
> Seluruh gambar README menggunakan path relatif. Pastikan nama file, huruf kapital, ekstensi, dan struktur direktorinya sama dengan yang ada di repository.

<p align="right"><a href="#readme-top">Kembali ke atas</a></p>

## Teknologi

| Lapisan | Teknologi | Kegunaan |
| --- | --- | --- |
| Backend | Laravel 13, PHP 8.2+ | Logika aplikasi, routing, validasi, autentikasi, dan akses database |
| Database | PostgreSQL | Penyimpanan dan pengelolaan data relasional |
| Frontend | Blade, Bootstrap 5, HTML5, CSS3 | Template, komponen antarmuka, dan tata letak responsif |
| Interaksi | JavaScript | Interaksi dan manipulasi halaman |
| Visualisasi | Chart.js | Grafik dan visualisasi data dashboard |
| Dependency manager | Composer, NPM | Pengelolaan dependensi backend dan frontend |
| Build tool | Vite | Kompilasi dan optimasi aset frontend |
| Version control | Git, GitHub | Pengelolaan versi dan kolaborasi kode |

<p align="right"><a href="#readme-top">Kembali ke atas</a></p>

## Persyaratan Sistem

Sebelum melakukan instalasi, pastikan perangkat telah memiliki:

| Kebutuhan | Versi atau Keterangan |
| --- | --- |
| PHP | 8.2 atau lebih baru |
| Composer | Versi terbaru yang kompatibel |
| PostgreSQL | Server database aktif |
| Node.js dan NPM | Untuk aset frontend |
| Git | Untuk clone dan version control |
| Web server | Apache, Nginx, atau Laravel development server |

Ekstensi PHP yang direkomendasikan:

```text
php-pgsql     php-mbstring     php-xml       php-curl
php-zip       php-bcmath       php-fileinfo  php-tokenizer
```

## Instalasi

### Instalasi Cepat

```bash
# 1. Clone repository
git clone https://github.com/Rahmyall/dashboard-monitoring-produktivitas-transaksi-jasa.git
cd dashboard-monitoring-produktivitas-transaksi-jasa

# 2. Instal dependensi
composer install
npm install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate

# 4. Migrasi database dan buat storage link
php artisan migrate --seed
php artisan storage:link

# 5. Jalankan aplikasi
npm run dev
php artisan serve
```

Untuk Windows Command Prompt, gunakan `copy .env.example .env` sebagai pengganti perintah `cp .env.example .env`.

Aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

### Konfigurasi Database

Buka file `.env`, kemudian sesuaikan konfigurasi berikut:

```env
APP_NAME="Dashboard Monitoring Produktivitas Karyawan dan Transaksi Jasa"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=dashboard_monitoring_produktivitas_jasa
DB_USERNAME=postgres
DB_PASSWORD=
```

<details>
<summary><strong>Lihat instalasi langkah demi langkah</strong></summary>

1. Clone repository.

   ```bash
   git clone https://github.com/Rahmyall/dashboard-monitoring-produktivitas-transaksi-jasa.git
   cd dashboard-monitoring-produktivitas-transaksi-jasa
   ```

2. Instal dependensi PHP dan frontend.

   ```bash
   composer install
   npm install
   ```

3. Salin file environment dan generate application key.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Sesuaikan koneksi PostgreSQL pada file `.env`.

5. Jalankan migrasi database.

   ```bash
   php artisan migrate
   ```

6. Apabila tersedia seeder:

   ```bash
   php artisan db:seed
   ```

7. Atau jalankan keduanya sekaligus:

   ```bash
   php artisan migrate --seed
   ```

8. Buat symbolic link storage.

   ```bash
   php artisan storage:link
   ```

9. Jalankan aset frontend dan development server.

   ```bash
   npm run dev
   php artisan serve
   ```

10. Untuk build produksi, gunakan:

   ```bash
   npm run build
   ```

</details>

<p align="right"><a href="#readme-top">Kembali ke atas</a></p>

## Perintah Pengembangan

| Kebutuhan | Perintah |
| --- | --- |
| Membersihkan seluruh cache aplikasi | `php artisan optimize:clear` |
| Membuat cache konfigurasi | `php artisan config:cache` |
| Membuat cache route | `php artisan route:cache` |
| Membuat cache view | `php artisan view:cache` |
| Menjalankan pengujian | `php artisan test` |
| Menampilkan daftar route | `php artisan route:list` |
| Menjalankan frontend development server | `npm run dev` |
| Membuat aset produksi | `npm run build` |

## Struktur Database

Database dikelompokkan berdasarkan domain proses bisnis agar pengelolaan modul lebih mudah dipahami.

| Modul | Tabel Utama |
| --- | --- |
| Autentikasi dan akses | `users`, `roles`, `permissions`, `role_user`, `permission_role` |
| Organisasi dan karyawan | `departments`, `positions`, `employees`, `work_schedules`, `employee_schedules` |
| Absensi dan cuti | `attendances`, `leave_requests` |
| Produktivitas | `performance_periods`, `performance_indicators`, `employee_targets`, `employee_activities`, `employee_performances` |
| Pelanggan dan jasa | `customers`, `service_categories`, `services` |
| Transaksi jasa | `service_orders`, `service_order_items`, `service_order_status_histories`, `service_assignments` |
| Keuangan transaksi | `invoices`, `payments`, `expenses` |
| Kepuasan pelanggan | `customer_feedback`, `customer_complaints` |
| Sistem pendukung | `notifications`, `audit_logs`, `system_settings` |

> [!IMPORTANT]
> Nama tabel pivot role dan permission dapat berbeda sesuai package atau implementasi hak akses yang digunakan.

## Struktur Direktori Gambar

```text
public/
└── backend/
    └── assets/
        └── img/
            ├── logo.png
            └── readme/
                ├── dashboard-preview.png
                ├── login-page.png
                ├── dashboard-eksekutif.png
                ├── data-karyawan.png
                ├── transaksi-jasa.png
                └── kepuasan-pelanggan.png
```

<details>
<summary><strong>Catatan evaluasi arsitektur dan data lineage</strong></summary>

### Kondisi Saat Ini

Rancangan database telah mencakup sebagian besar kebutuhan dashboard eksekutif. Namun, desain masih cenderung data-oriented dan perlu diperkuat agar lebih process-oriented.

Jalur pembuktian proses bisnis berikut perlu dibuat lebih utuh:

> Siapa mengerjakan apa, kapan pekerjaan dilakukan, berdasarkan jadwal apa, diverifikasi oleh siapa, ditagihkan melalui invoice mana, dibayar melalui transaksi yang mana, dan dinilai oleh pelanggan yang mana.

### Risiko Utama

Ketidakutuhan relasi antarmodul dapat menyulitkan:

- Pelacakan pekerjaan setiap karyawan
- Verifikasi realisasi aktivitas
- Pengukuran produktivitas secara objektif
- Pencocokan pekerjaan dengan invoice
- Rekonsiliasi invoice dan pembayaran
- Atribusi feedback kepada karyawan yang tepat
- Audit sumber data dashboard

Kondisi tersebut dapat menimbulkan false accuracy, yaitu angka dashboard terlihat rinci dan meyakinkan, tetapi asal-usul, konsistensi, serta validitasnya belum sepenuhnya dapat dibuktikan.

### Prioritas Penyempurnaan

- Hubungkan aktivitas karyawan dengan item pesanan jasa
- Hubungkan absensi dengan jadwal kerja yang berlaku
- Perjelas penugasan, realisasi, dan verifikasi pekerjaan
- Tetapkan aturan perubahan status setiap proses
- Perkuat hubungan pesanan, invoice, pembayaran, dan pengeluaran
- Hubungkan feedback dengan karyawan atau item jasa terkait
- Simpan histori persetujuan dan perubahan status
- Terapkan validasi dan constraint pada tingkat database

### Kesimpulan Teknis

Kualitas dashboard tidak hanya ditentukan oleh jumlah tabel atau indikator, tetapi juga oleh tersedianya data lineage yang jelas, konsisten, terverifikasi, dan dapat diaudit.

</details>

<p align="right"><a href="#readme-top">Kembali ke atas</a></p>

## Keamanan

Mekanisme keamanan yang digunakan atau direkomendasikan meliputi:

- Autentikasi pengguna
- Otorisasi berbasis role dan permission
- Proteksi CSRF
- Validasi input
- Hashing kata sandi
- Middleware hak akses
- Pembatasan akses route
- Perlindungan mass assignment
- Pengelolaan session
- Audit log aktivitas
- Soft delete untuk data tertentu

> [!WARNING]
> Jangan mengunggah file `.env`, credential database, API key, token, atau informasi sensitif lainnya ke repository publik.

## Kontribusi

Kontribusi dapat dilakukan melalui alur berikut:

1. Fork repository ini.
2. Buat branch baru.
3. Lakukan perubahan dan pengujian.
4. Commit dengan pesan yang jelas.
5. Push branch dan buat Pull Request.

```bash
git checkout -b feature/nama-fitur
git add .
git commit -m "feat: menambahkan nama fitur"
git push origin feature/nama-fitur
```

## Konvensi Commit

| Tipe | Kegunaan |
| --- | --- |
| `feat` | Menambahkan fitur baru |
| `fix` | Memperbaiki bug atau kesalahan aplikasi |
| `docs` | Memperbarui dokumentasi |
| `style` | Memperbaiki format tanpa mengubah fungsi |
| `refactor` | Menyederhanakan atau merestrukturisasi kode |
| `test` | Menambahkan atau memperbarui pengujian |
| `chore` | Memperbarui konfigurasi atau pekerjaan pendukung |

## Pelaporan Masalah

Laporkan bug melalui GitHub Issues dengan menyertakan:

- Deskripsi masalah
- Langkah reproduksi
- Hasil yang diharapkan
- Hasil yang terjadi
- Screenshot atau log kesalahan jika tersedia
- Versi PHP dan Laravel
- Sistem operasi yang digunakan

## Lisensi

Proyek ini didistribusikan berdasarkan ketentuan yang tercantum pada file `LICENSE`.

<div align="center">
  <p>Dikembangkan untuk mendukung proses monitoring dan pengambilan keputusan pada perusahaan jasa.</p>
  <strong>Dashboard Monitoring Produktivitas Karyawan dan Transaksi Jasa</strong>
  <br><br>
  <a href="#readme-top">Kembali ke atas</a>
</div>
