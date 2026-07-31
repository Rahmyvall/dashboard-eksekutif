<div align="center">

<img
src="public/backend/assets/img/logo.png"
width="130"
alt="Logo Dashboard Monitoring Perusahaan Jasa"
/>

Dashboard Monitoring Perusahaan Jasa

Sistem informasi berbasis web untuk memonitor produktivitas karyawan, absensi, transaksi jasa, pendapatan, pengeluaran, dan kepuasan pelanggan secara terintegrasi.



<br>

<img
src="public/backend/assets/img/readme/dashboard-preview.png"
width="900"
alt="Preview Dashboard Monitoring Perusahaan Jasa"
/>

<br>

<em>
Tampilan Dashboard Eksekutif untuk memonitor produktivitas karyawan,
transaksi jasa, pendapatan, dan aktivitas operasional perusahaan.
</em>

</div>

Daftar Isi

Tentang Aplikasi

Proses Bisnis

Flowchart

Tampilan Aplikasi

Fitur Utama

Teknologi yang Digunakan

Persyaratan Sistem

Instalasi

Struktur Modul Database

Struktur Direktori Gambar

Keamanan

Kontribusi

Lisensi

Tentang Aplikasi

Dashboard Monitoring Perusahaan Jasa merupakan aplikasi berbasis web yang dikembangkan untuk membantu perusahaan dalam memonitor dan mengelola aktivitas operasional secara terintegrasi.

Aplikasi ini mencakup pengelolaan:

Data karyawan

Departemen dan jabatan

Jadwal kerja

Absensi dan cuti

Target serta penilaian kinerja

Pelanggan dan layanan

Transaksi jasa

Invoice dan pembayaran

Pengeluaran perusahaan

Kepuasan serta keluhan pelanggan

Laporan dan dashboard eksekutif

Informasi utama ditampilkan dalam dashboard interaktif agar manajemen dapat memantau kondisi perusahaan dan mengambil keputusan berdasarkan data yang tersedia.

Proses Bisnis

Proses bisnis aplikasi menggambarkan hubungan antara pelanggan, bagian operasional, karyawan, keuangan, HRD, dan manajemen dalam menjalankan layanan perusahaan. Seluruh aktivitas utama dicatat ke dalam sistem agar dapat dipantau melalui dashboard dan laporan manajemen.

Aktor yang Terlibat

Aktor

Tanggung Jawab Utama

Super Admin

Mengelola konfigurasi sistem, pengguna, role, permission, dan data master.

Admin/Operasional

Mencatat pelanggan, pesanan jasa, detail layanan, penugasan, serta progres pekerjaan.

HRD

Mengelola karyawan, departemen, jabatan, jadwal kerja, absensi, cuti, dan penilaian kinerja.

Karyawan

Melakukan absensi, melihat jadwal, menjalankan tugas, mencatat aktivitas, dan memperbarui progres pekerjaan.

Keuangan

Membuat invoice, memverifikasi pembayaran, mencatat pengeluaran, dan menyusun laporan keuangan operasional.

Manajer/Direktur

Memantau KPI, produktivitas, transaksi, pendapatan, pengeluaran, keluhan, dan laporan eksekutif.

Pelanggan

Mengajukan kebutuhan jasa, melakukan pembayaran, memberikan penilaian, dan menyampaikan keluhan.

Alur Proses Bisnis Utama

Konfigurasi dan data masterSuper Admin atau admin menyiapkan data pengguna, role, permission, departemen, jabatan, karyawan, kategori jasa, layanan, harga, dan pengaturan sistem.

Registrasi pelanggan dan permintaan jasaAdmin mencatat data pelanggan serta kebutuhan layanan. Sistem kemudian membuat pesanan jasa beserta detail item layanan.

Validasi dan persetujuan pesananData pesanan diperiksa, termasuk jenis layanan, harga, jadwal, lokasi, kebutuhan tenaga kerja, dan informasi pendukung. Pesanan yang valid dilanjutkan ke proses penugasan.

Penjadwalan dan penugasan pekerjaanAdmin atau manajer operasional memilih karyawan berdasarkan kompetensi, jadwal kerja, dan beban pekerjaan. Sistem mencatat penanggung jawab serta target penyelesaian.

Pelaksanaan layananKaryawan menjalankan pekerjaan, melakukan absensi, mencatat aktivitas, mengunggah bukti pekerjaan apabila diperlukan, dan memperbarui progres pesanan.

Monitoring dan verifikasi hasilAdmin atau supervisor memonitor progres dan memverifikasi hasil pekerjaan. Apabila pekerjaan belum sesuai, pesanan dikembalikan untuk diperbaiki. Jika sudah sesuai, status pesanan diubah menjadi selesai.

Pembuatan invoice dan pembayaranSetelah pekerjaan selesai atau sesuai termin yang ditentukan, bagian keuangan membuat invoice. Pembayaran pelanggan dicatat dan diverifikasi hingga status invoice menjadi lunas atau sebagian dibayar.

Pencatatan pengeluaranBiaya yang berkaitan dengan transaksi atau operasional dicatat sebagai pengeluaran untuk menghasilkan perhitungan pendapatan bersih yang lebih akurat.

Penilaian dan keluhan pelangganPelanggan memberikan penilaian terhadap kualitas layanan, kinerja karyawan, ketepatan waktu, dan harga. Keluhan yang masuk ditindaklanjuti sampai dinyatakan selesai.

Evaluasi kinerja dan dashboard manajemenSistem mengolah data absensi, aktivitas, target, transaksi, pendapatan, pengeluaran, kepuasan, dan keluhan menjadi indikator KPI serta dashboard eksekutif.

Matriks Proses dan Output

Tahap

Input

Proses Sistem

Output

Data master

Data organisasi, karyawan, layanan, harga

Validasi dan penyimpanan data master

Data referensi sistem

Permintaan jasa

Data pelanggan dan kebutuhan layanan

Pembuatan pesanan dan detail layanan

Nomor pesanan jasa

Penugasan

Jadwal, kompetensi, dan beban kerja

Penentuan karyawan pelaksana

Surat/daftar penugasan

Pelaksanaan

Aktivitas, absensi, dan progres

Pencatatan serta monitoring pekerjaan

Riwayat progres pekerjaan

Verifikasi

Hasil pekerjaan dan bukti pendukung

Pemeriksaan kesesuaian hasil

Status selesai atau revisi

Penagihan

Data pesanan selesai dan nilai transaksi

Pembuatan invoice

Invoice pelanggan

Pembayaran

Bukti dan nominal pembayaran

Verifikasi pembayaran

Status pembayaran

Pengeluaran

Biaya transaksi dan operasional

Pencatatan biaya

Rekap pengeluaran

Umpan balik

Rating, komentar, atau keluhan

Analisis kepuasan dan tindak lanjut

Nilai kepuasan dan status keluhan

Pelaporan

Data seluruh modul

Agregasi dan visualisasi

Dashboard dan laporan manajemen

Aturan Bisnis Utama

Pengguna hanya dapat mengakses menu dan tindakan sesuai role serta permission.

Pesanan tidak dapat masuk ke tahap pelaksanaan sebelum data pelanggan, layanan, harga, dan penugasan dinyatakan valid.

Karyawan yang dinonaktifkan tidak dapat menerima penugasan baru.

Jadwal penugasan harus mempertimbangkan benturan jadwal dan status ketersediaan karyawan.

Status pekerjaan harus diperbarui secara berurutan dan disimpan dalam riwayat status.

Invoice hanya dibuat untuk pesanan yang telah memenuhi syarat penagihan.

Nilai pembayaran tidak boleh melebihi sisa tagihan tanpa proses koreksi atau pengembalian dana.

Keluhan tidak dapat ditutup sebelum tindak lanjut dan hasil penyelesaian dicatat.

Perubahan data penting harus direkam pada audit log.

Data yang dihapus menggunakan mekanisme soft delete apabila masih diperlukan untuk histori dan pelaporan.

Flowchart

Flowchart berikut menggunakan sintaks Mermaid dan dapat ditampilkan secara langsung pada GitHub, GitLab, atau editor Markdown yang mendukung Mermaid.

Flowchart Proses Bisnis Utama

flowchart TD
    A([Mulai]) --> B[Login ke Sistem]
    B --> C{Autentikasi Berhasil?}
    C -- Tidak --> D[Tampilkan Pesan Kesalahan]
    D --> B
    C -- Ya --> E[Validasi Role dan Permission]
    E --> F[Kelola Data Master]
    F --> G[Catat Data Pelanggan]
    G --> H[Buat Pesanan Jasa]
    H --> I[Validasi Layanan, Harga, dan Jadwal]
    I --> J{Data Pesanan Valid?}
    J -- Tidak --> K[Perbaiki Data Pesanan]
    K --> I
    J -- Ya --> L[Tentukan dan Tugaskan Karyawan]
    L --> M[Karyawan Melaksanakan Pekerjaan]
    M --> N[Catat Absensi, Aktivitas, dan Progres]
    N --> O[Supervisor Memverifikasi Hasil]
    O --> P{Hasil Sesuai?}
    P -- Tidak --> Q[Kembalikan untuk Perbaikan]
    Q --> M
    P -- Ya --> R[Ubah Status Pesanan Menjadi Selesai]
    R --> S[Buat Invoice]
    S --> T[Catat dan Verifikasi Pembayaran]
    T --> U{Invoice Lunas?}
    U -- Belum --> V[Catat Sisa Tagihan]
    V --> T
    U -- Ya --> W[Catat Pengeluaran Terkait]
    W --> X[Input Feedback atau Keluhan Pelanggan]
    X --> Y{Ada Keluhan?}
    Y -- Ya --> Z[Tindak Lanjut Keluhan]
    Z --> AA[Verifikasi Penyelesaian Keluhan]
    AA --> AB[Perbarui Dashboard dan Laporan]
    Y -- Tidak --> AB
    AB --> AC[Manajemen Melakukan Evaluasi]
    AC --> AD([Selesai])

Flowchart Absensi Karyawan

flowchart TD
    A([Mulai]) --> B[Karyawan Login]
    B --> C[Memilih Menu Absensi]
    C --> D{Sudah Absen Masuk?}
    D -- Belum --> E[Catat Waktu Masuk]
    E --> F[Bandingkan dengan Jadwal Kerja]
    F --> G[Hitung Status Tepat Waktu atau Terlambat]
    G --> H[Simpan Absensi Masuk]
    D -- Sudah --> I{Sudah Absen Pulang?}
    I -- Belum --> J[Catat Waktu Pulang]
    J --> K[Hitung Durasi Kerja dan Lembur]
    K --> L[Simpan Absensi Pulang]
    I -- Sudah --> M[Tampilkan Riwayat Absensi]
    H --> M
    L --> M
    M --> N([Selesai])

Flowchart Pengajuan Cuti

flowchart TD
    A([Mulai]) --> B[Karyawan Mengisi Form Cuti]
    B --> C[Validasi Tanggal, Jenis, dan Alasan]
    C --> D{Data Lengkap?}
    D -- Tidak --> E[Tampilkan Kesalahan Validasi]
    E --> B
    D -- Ya --> F[Periksa Sisa Cuti dan Benturan Jadwal]
    F --> G{Memenuhi Syarat?}
    G -- Tidak --> H[Pengajuan Ditolak Sistem]
    G -- Ya --> I[Kirim ke Atasan atau HRD]
    I --> J{Disetujui?}
    J -- Tidak --> K[Catat Alasan Penolakan]
    J -- Ya --> L[Kurangi Saldo Cuti]
    L --> M[Perbarui Jadwal dan Status Kehadiran]
    H --> N[Kirimi Notifikasi kepada Karyawan]
    K --> N
    M --> N
    N --> O([Selesai])

Flowchart Penilaian Kinerja

flowchart TD
    A([Mulai]) --> B[Tentukan Periode Penilaian]
    B --> C[Tentukan KPI dan Bobot]
    C --> D[Tetapkan Target Karyawan]
    D --> E[Karyawan Mencatat Aktivitas dan Realisasi]
    E --> F[Sistem Menghitung Pencapaian]
    F --> G[Atasan Melakukan Verifikasi]
    G --> H{Data Valid?}
    H -- Tidak --> I[Kembalikan untuk Koreksi]
    I --> E
    H -- Ya --> J[Hitung Nilai Akhir]
    J --> K[Tentukan Kategori dan Peringkat]
    K --> L[Simpan Hasil Penilaian]
    L --> M[Tampilkan pada Dashboard Produktivitas]
    M --> N([Selesai])

Flowchart Penanganan Keluhan Pelanggan

flowchart TD
    A([Mulai]) --> B[Pelanggan Menyampaikan Keluhan]
    B --> C[Admin Mencatat Keluhan]
    C --> D[Klasifikasi Jenis dan Prioritas]
    D --> E[Tugaskan Penanggung Jawab]
    E --> F[Lakukan Investigasi]
    F --> G[Tentukan Tindakan Penyelesaian]
    G --> H[Catat Hasil Tindak Lanjut]
    H --> I{Pelanggan Menerima Solusi?}
    I -- Tidak --> J[Eskalasi ke Supervisor atau Manajer]
    J --> F
    I -- Ya --> K[Ubah Status Menjadi Selesai]
    K --> L[Simpan Waktu Penyelesaian]
    L --> M[Perbarui Statistik Keluhan dan Kepuasan]
    M --> N([Selesai])

Tampilan Aplikasi

Halaman Login

<div align="center">

<img
src="public/backend/assets/img/readme/login-page.png"
width="850"
alt="Halaman Login Dashboard Monitoring Perusahaan Jasa"
/>

</div>

Halaman login digunakan untuk mengamankan akses ke dalam sistem. Setiap pengguna hanya dapat mengakses fitur dan menu sesuai dengan peran serta hak akses yang telah diberikan.

Dashboard Eksekutif

<div align="center">

<img
src="public/backend/assets/img/readme/dashboard-eksekutif.png"
width="850"
alt="Dashboard Eksekutif"
/>

</div>

Dashboard Eksekutif menampilkan informasi penting perusahaan, antara lain:

Produktivitas karyawan

Rekapitulasi absensi

Jumlah transaksi jasa

Pendapatan dan pengeluaran

Invoice yang belum dibayar

Tingkat kepuasan pelanggan

Jumlah keluhan pelanggan

Manajemen Karyawan

<div align="center">

<img
src="public/backend/assets/img/readme/data-karyawan.png"
width="850"
alt="Halaman Manajemen Data Karyawan"
/>

</div>

Modul Manajemen Karyawan digunakan untuk mengelola:

Data karyawan

Departemen

Jabatan

Jadwal kerja

Penempatan jadwal

Status kepegawaian

Transaksi Jasa

<div align="center">

<img
src="public/backend/assets/img/readme/transaksi-jasa.png"
width="850"
alt="Halaman Transaksi Jasa"
/>

</div>

Modul Transaksi Jasa digunakan untuk mengelola:

Pesanan pelanggan

Detail layanan

Penugasan pekerjaan

Riwayat status pekerjaan

Invoice

Pembayaran

Pengeluaran transaksi

Kepuasan Pelanggan

<div align="center">

<img
src="public/backend/assets/img/readme/kepuasan-pelanggan.png"
width="850"
alt="Halaman Kepuasan Pelanggan"
/>

</div>

Modul Kepuasan Pelanggan digunakan untuk mencatat:

Penilaian kualitas pelayanan

Penilaian kinerja karyawan

Penilaian ketepatan waktu

Penilaian harga

Komentar pelanggan

Keluhan pelanggan

Status penyelesaian keluhan

Galeri Tampilan

<table>
    <tr>
        <td align="center" width="50%">
            <img
                src="public/backend/assets/img/readme/login-page.png"
                width="100%"
                alt="Halaman Login"
            />
            <br>
            <strong>Halaman Login</strong>
        </td>
        <td align="center" width="50%">
            <img
                src="public/backend/assets/img/readme/dashboard-eksekutif.png"
                width="100%"
                alt="Dashboard Eksekutif"
            />
            <br>
            <strong>Dashboard Eksekutif</strong>
        </td>
    </tr>
    <tr>
        <td align="center" width="50%">
            <img
                src="public/backend/assets/img/readme/data-karyawan.png"
                width="100%"
                alt="Manajemen Karyawan"
            />
            <br>
            <strong>Manajemen Karyawan</strong>
        </td>
        <td align="center" width="50%">
            <img
                src="public/backend/assets/img/readme/transaksi-jasa.png"
                width="100%"
                alt="Transaksi Jasa"
            />
            <br>
            <strong>Transaksi Jasa</strong>
        </td>
    </tr>
</table>

Fitur Utama

1. Autentikasi dan Hak Akses

Login dan logout pengguna

Manajemen akun pengguna

Manajemen role dan permission

Pembatasan menu berdasarkan hak akses

Manajemen sesi pengguna

Perubahan kata sandi

Status aktif dan nonaktif pengguna

2. Manajemen Karyawan

Data karyawan

Data departemen

Data jabatan

Jadwal kerja

Penempatan jadwal karyawan

Status kepegawaian

Riwayat data karyawan

3. Absensi dan Cuti

Pencatatan jam masuk

Pencatatan jam pulang

Perhitungan keterlambatan

Perhitungan lembur

Status kehadiran

Pengajuan cuti

Persetujuan cuti

Penolakan cuti

Riwayat pengajuan cuti

4. Produktivitas Karyawan

Periode penilaian

Indikator kinerja atau KPI

Target karyawan

Aktivitas pekerjaan

Realisasi pekerjaan

Penilaian kinerja

Persentase pencapaian target

Peringkat karyawan

Riwayat hasil penilaian

5. Pelanggan dan Layanan

Data pelanggan

Kategori jasa

Data layanan

Harga layanan

Status layanan

Riwayat pelanggan

6. Transaksi Jasa

Pembuatan pesanan jasa

Detail item pesanan

Riwayat status pesanan

Penugasan pekerjaan

Invoice pelanggan

Pencatatan pembayaran

Pencatatan pengeluaran

Monitoring progres pekerjaan

Riwayat transaksi

7. Kepuasan Pelanggan

Penilaian kualitas pelayanan

Penilaian kinerja karyawan

Penilaian ketepatan waktu

Penilaian harga

Komentar pelanggan

Keluhan pelanggan

Tindak lanjut keluhan

Status penyelesaian keluhan

8. Dashboard Eksekutif

Statistik jumlah karyawan

Rekapitulasi absensi

Rekapitulasi pengajuan cuti

Produktivitas karyawan

Pencapaian target

Jumlah pelanggan

Jumlah transaksi jasa

Total pendapatan perusahaan

Total pengeluaran perusahaan

Invoice belum dibayar

Tingkat kepuasan pelanggan

Jumlah keluhan pelanggan

Grafik perkembangan transaksi

Grafik pendapatan dan pengeluaran

9. Fitur Pendukung

Notifikasi pengguna

Audit log aktivitas

Pengaturan sistem

Pencarian data

Filter data

Pengurutan data

Pagination

Soft delete

Restore data

Ekspor laporan

Tampilan responsif

Validasi formulir

Konfirmasi tindakan

Teknologi yang Digunakan

Teknologi

Keterangan

Laravel 13

Framework backend aplikasi

PHP 8.2+

Bahasa pemrograman backend

PostgreSQL

Sistem manajemen basis data

Blade

Template engine Laravel

Bootstrap 5

Framework antarmuka pengguna

JavaScript

Interaksi dan manipulasi halaman

Chart.js

Visualisasi grafik dashboard

HTML5

Struktur halaman aplikasi

CSS3

Desain dan tata letak halaman

Composer

Manajemen dependensi PHP

NPM

Manajemen dependensi frontend

Vite

Build tool untuk aset frontend

Git

Version control

GitHub

Repository dan kolaborasi kode

Persyaratan Sistem

Pastikan perangkat telah memiliki perangkat lunak berikut:

PHP 8.2 atau lebih baru

Composer

PostgreSQL

Node.js

NPM

Git

Web server Apache atau Nginx

Ekstensi PHP yang direkomendasikan:

php-pgsql

php-mbstring

php-xml

php-curl

php-zip

php-bcmath

php-fileinfo

php-tokenizer

Instalasi

1. Clone Repository

git clone https://github.com/USERNAME/NAMA-REPOSITORY.git

Masuk ke direktori aplikasi:

cd NAMA-REPOSITORY

2. Instal Dependensi PHP

composer install

3. Instal Dependensi Frontend

npm install

4. Salin File Environment

Untuk Linux atau macOS:

cp .env.example .env

Untuk Windows Command Prompt:

copy .env.example .env

5. Generate Application Key

php artisan key:generate

6. Konfigurasi Database

Buka file .env, kemudian sesuaikan konfigurasi berikut:

APP_NAME="Dashboard Monitoring Perusahaan Jasa"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=dashboard_monitoring_jasa
DB_USERNAME=postgres
DB_PASSWORD=

7. Jalankan Migrasi Database

php artisan migrate

Apabila aplikasi menyediakan data awal melalui seeder, jalankan:

php artisan db:seed

Atau jalankan migrasi beserta seeder:

php artisan migrate --seed

8. Buat Symbolic Link Storage

php artisan storage:link

9. Jalankan Build Aset Frontend

Untuk mode pengembangan:

npm run dev

Untuk mode produksi:

npm run build

10. Jalankan Aplikasi

php artisan serve

Akses aplikasi melalui alamat berikut:

http://127.0.0.1:8000

Perintah Pengembangan

Membersihkan cache aplikasi:

php artisan optimize:clear

Membuat ulang cache konfigurasi:

php artisan config:cache

Membuat cache route:

php artisan route:cache

Membuat cache view:

php artisan view:cache

Menjalankan pengujian:

php artisan test

Menampilkan daftar route:

php artisan route:list

Struktur Modul Database

Database aplikasi dikelompokkan menjadi beberapa modul utama:

Autentikasi dan akses

Role dan permission

Organisasi dan karyawan

Absensi dan cuti

Produktivitas karyawan

Pelanggan dan jasa

Transaksi jasa

Kepuasan pelanggan

Notifikasi dan audit log

Pengaturan sistem

Pendukung dashboard

Tabel Utama

users
roles
permissions
role_user
permission_role

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
service_assignments

invoices
payments
expenses

customer_feedback
customer_complaints

notifications
audit_logs
system_settings

Nama tabel pivot role dan permission dapat berbeda tergantung implementasi package atau struktur database yang digunakan.

Struktur Direktori Gambar

Agar seluruh gambar pada README dapat tampil dengan benar, gunakan struktur direktori berikut:

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

Pastikan penulisan nama file, huruf kapital, dan ekstensi gambar sesuai dengan file yang terdapat di repository.

Keamanan

Beberapa mekanisme keamanan yang digunakan dalam aplikasi:

Autentikasi pengguna

Otorisasi berdasarkan role dan permission

Proteksi CSRF

Validasi input

Hashing kata sandi

Middleware hak akses

Pembatasan akses route

Audit log aktivitas pengguna

Perlindungan mass assignment

Pengelolaan session

Soft delete untuk data tertentu

Jangan mengunggah file .env, credential database, API key, atau informasi sensitif lainnya ke repository publik.

Kontribusi

Kontribusi terhadap pengembangan aplikasi dapat dilakukan melalui langkah berikut:

Fork repository ini.

Buat branch fitur baru.

git checkout -b feature/nama-fitur

Lakukan perubahan dan commit.

git commit -m "feat: menambahkan nama fitur"

Push branch ke repository.

git push origin feature/nama-fitur

Buat Pull Request melalui GitHub.

Gunakan format commit yang jelas dan konsisten, misalnya:

feat: menambahkan fitur baru
fix: memperbaiki kesalahan aplikasi
docs: memperbarui dokumentasi
style: memperbaiki format kode
refactor: menyederhanakan struktur kode
test: menambahkan pengujian
chore: memperbarui konfigurasi proyek

Pelaporan Masalah

Apabila menemukan bug atau masalah pada aplikasi, buat laporan melalui halaman berikut:

https://github.com/USERNAME/NAMA-REPOSITORY/issues

Sertakan informasi berikut:

Deskripsi masalah

Langkah untuk menghasilkan masalah

Hasil yang diharapkan

Hasil yang terjadi

Screenshot apabila diperlukan

Versi PHP dan Laravel

Sistem operasi yang digunakan

Lisensi

Proyek ini didistribusikan berdasarkan lisensi yang tercantum pada file LICENSE.

Pengembang

<div align="center">

Dikembangkan untuk mendukung proses monitoring dan pengambilan keputusan pada perusahaan jasa.

Dashboard Monitoring Perusahaan Jasa

</div>