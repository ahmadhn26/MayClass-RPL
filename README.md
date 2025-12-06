# MayClass: Sistem Informasi Bimbingan Belajar

MayClass adalah sistem informasi bimbingan belajar (e-learning) berbasis website yang dirancang untuk mendigitalkan dan mengelola proses bisnis di Bimbel MayClass.

Proyek ini dikembangkan oleh Tim 1 3SD2 sebagai bagian dari pemenuhan tugas mata kuliah **Rekayasa Perangkat Lunak** di Politeknik Statistika STIS.

## Problem

Klien (Bimbel MayClass) saat ini mengelola seluruh operasional secara manual. Informasi seperti jadwal, materi, dan data siswa masih tersebar dalam bentuk file PDF atau tautan Google Drive. Proses ini tidak efisien, menyulitkan siswa dan orang tua untuk mendapatkan informasi terpusat, dan menghambat pendokumentasian progres belajar bagi siswa.

## 💡 Solusi

Website MayClass ini hadir sebagai platform terintegrasi yang berfungsi sebagai:

1. **Pusat Informasi:** Menyediakan informasi resmi mengenai profil, paket harga, jadwal, dan profil tentor.
2. **Manajemen Akademik:** Mengelola data siswa, tentor, materi pembelajaran, dan bank soal secara terstruktur.
3. **Platform E-Learning:** Memfasilitasi siswa untuk mengakses materi, mengerjakan kuis/tugas, dan melihat progres belajar.
4. **Alat Branding:** Menampilkan testimoni dan foto kegiatan untuk meningkatkan kepercayaan calon siswa.

## ✨ Fitur Utama

Sistem ini memiliki 5 peran pengguna utama:

* **Pengunjung (Publik):**

  * Melihat halaman informasi: profil bimbel, paket belajar, dan profil tentor.
  * Melihat kontak dan testimoni.
  * Melakukan registrasi untuk menjadi siswa.
* **Siswa:**

  * Login ke sistem.
  * Mengakses materi pembelajaran dan bank soal.
  * Mengerjakan tugas atau kuis.
  * Melihat progres belajar.
* **Tentor:**

  * Login ke sistem.
  * Mengelola (mengunggah, update, hapus) materi dan bank soal.
* **Admin Utama:**

  * Mengelola seluruh data pengguna (siswa, tentor, admin lainnya).
* **Admin Keuangan:**

  * Mengelola tagihan siswa.
  * Memverifikasi pembayaran yang masuk.

## 🛠️ Tumpukan Teknologi (Technology Stack)

* **Backend:** Laravel (PHP Framework)
* **Frontend:** Vue.js & Blade Templates
* **Styling:** Tailwind CSS
* **Database:** MySQL

## 🎨 Desain & Wireframe

Rancangan antarmuka (UI/UX) dan prototipe interaktif untuk proyek ini dibuat menggunakan Figma.

* **Tautan Utama Desain:** [Lihat Desain Lengkap di Figma](https://www.figma.com/design/FYcvU8p4W8qNuyghlN4VFk/WIREFRAME?node-id=134-348&t=359dHXDoq8JX47xF-1)
* **Prototipe Alur Siswa:** [Coba Alur Siswa](https://www.figma.com/proto/FYcvU8p4W8qNuyghlN4VFk/WIREFRAME?node-id-1-188&p=f&t=UEcMAvLIo68NX2km-1&scaling-min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A188)
* **Prototipe Alur Tentor:** [Coba Alur Tentor](https://www.figma.com/proto/FYcvU8p4W8qNuyghlN4VFk/WIREFRAME?node-id=134-1161&p=f&t=2VXB CyG9sHr6oUd-1&scaling-min-zoom&content-scaling=fixed&page-id=134%3A348)

## 🚀 Instalasi dan Menjalankan Proyek

Untuk menjalankan proyek ini secara lokal, ikuti langkah-langkah berikut:

1. **Clone repositori:**

   ```bash
   git clone [https://github.com/viersss/MayClass.git](https://github.com/viersss/MayClass.git)
   cd MayClass
   ```
2. **Install dependensi Backend (Composer):**

   ```bash
   composer install
   ```
3. **Install dependensi Frontend (NPM):**

   ```bash
   npm install
   ```
4. **Setup file `.env`:**

   * Salin file `.env.example` menjadi `.env`.
   * ```bash
       cp .env.example .env
     ```
   * Buka file `.env` dan konfigurasikan koneksi database Anda (DB_DATABASE, DB_USERNAME, DB_PASSWORD). Untuk Laragon,
     gunakan username `root` dengan password **kosong** kecuali Anda sudah menentukannya secara manual.
5. **Generate Kunci Aplikasi Laravel:**

   ```bash
   php artisan key:generate
   ```
6. **Jalankan Migrasi Database:**

   * (Pastikan Anda sudah membuat database di MySQL sesuai nama di file `.env`)

   ```bash
   php artisan migrate
   ```
7. **(Opsional) Jalankan Database Seeder (jika ada):**

   ```bash
   php artisan db:seed
   ```
8. **Jalankan server pengembangan:**

   * Terminal 1 (Vite/NPM):
     ```bash
     npm run dev
     ```
   * Terminal 2 (Laravel/PHP):
     ```bash
     php artisan serve
     ```
9. Buka aplikasi di `http://localhost:8000`.

## 🔧 Troubleshooting

### Error `HY000/1045` saat login MySQL atau phpMyAdmin

Pesan `Access denied for user 'root'@'localhost'` akan muncul jika kredensial MySQL Anda tidak cocok dengan konfigurasi
server lokal. Terkadang phpMyAdmin menampilkan variasi pesan seperti `(using password: YES)` ataupun `(using password: NO)`.
Ikuti panduan berikut supaya aplikasi dapat terhubung kembali:

1. Coba login di phpMyAdmin menggunakan **username `root`** dan **password kosong** terlebih dahulu. Laragon secara bawaan
   tidak memberikan password pada akun `root`. Pastikan opsi "Remember me" dinonaktifkan ketika mencoba.
2. Jika login masih gagal, buka **Laragon → Menu → Database → mysql → Reset/Change password** kemudian atur password baru
   untuk akun `root`.
3. Perbarui file `.env` agar menggunakan kredensial terbaru:

   ```ini
   DB_USERNAME=root
   DB_PASSWORD=kata_sandi_baru_anda
   ```
4. Restart layanan MySQL melalui Laragon agar pengaturan baru aktif.
5. Uji kembali login via phpMyAdmin dengan username dan password yang sama seperti di `.env`.
6. Jika Anda hanya ingin menjalankan proyek secara cepat, biarkan password di `.env` tetap kosong. Aplikasi kini akan
   secara otomatis mencoba ulang koneksi MySQL tanpa password ketika mendeteksi Anda menggunakan akun `root` di lingkungan
   lokal.
7. Jalankan kembali migrasi aplikasi apabila sebelumnya gagal:

   ```bash
   php artisan migrate
   ```

Apabila Anda tidak ingin menggunakan akun `root`, buat pengguna baru di MySQL dengan hak akses yang dibutuhkan dan masukkan
credential tersebut ke dalam file `.env`.

### Error `SQLSTATE[42S02]: Base table or view not found: 1146 Table 'mayclass.sessions' doesn't exist`

Laravel MayClass dikonfigurasi menggunakan **database session driver**, sehingga tabel `sessions` wajib ada di database Anda.
Jika Anda melihat error di atas ketika menjalankan `php artisan serve`, lakukan langkah-langkah berikut:

1. Pastikan database yang dirujuk di `.env` sudah dibuat di MySQL/Laragon Anda.
2. Jalankan migrasi terbaru untuk membuat tabel `sessions` dan tabel lainnya yang mungkin tertinggal:

   ```bash
   php artisan migrate
   ```
3. Apabila Anda mengimpor `database/schema.sql` secara manual, pastikan skrip tersebut berhasil membuat tabel `sessions`.
   Anda dapat mengeceknya melalui phpMyAdmin atau menjalankan query berikut di MySQL:

   ```sql
   SHOW TABLES LIKE 'sessions';
   ```
4. Setelah tabel tersedia, restart server dengan perintah `php artisan serve` dan muat ulang halaman aplikasi.

**Catatan:** Mulai sekarang MayClass akan secara otomatis menggunakan _file session driver_ sementara apabila tabel
`sessions` belum ada atau pemeriksaan tabel gagal akibat kredensial database yang salah. Hal ini membuat aplikasi tetap
dapat dijalankan, namun pastikan Anda tetap menjalankan migrasi agar session kembali tersimpan di database.

### Error `Unknown column 'is_active' in 'field list'`

Fitur **Manajemen Tentor** menambahkan kolom baru `is_active` di tabel `users`. Jika Anda menarik pembaruan terbaru tapi belum
menjalankan migrasi, perintah provisioning akun demo pada saat `php artisan serve` akan gagal dengan error di atas. Solusinya:

1. Pastikan koneksi database di `.env` sudah benar dan database yang dimaksud tersedia.
2. Jalankan migrasi terbaru untuk membuat kolom tersebut:

   ```bash
   php artisan migrate
   ```
3. Setelah migrasi selesai, jalankan ulang `php artisan serve`. Aplikasi akan berjalan normal dan kolom `is_active` siap
   digunakan untuk mengatur status aktif/nonaktif tentor dari panel admin.

## 👥 Tim Developer (Kelompok 1 - 3SD2)

Proyek ini dikerjakan oleh:

| Nama                         | NIM       |
| :--------------------------- | :-------- |
| Ahmad Husein Nasution        | 222312952 |
| Henny Merry Astutik          | 222313120 |
| Johana Putri Natasya Sitorus | 222313150 |
| Lisa Fajrianti               | 222313174 |
| Triangga Hafid Rifa'i        | 222313408 |
| Xavier Yubin Raditio         | 222313427 |
| Yudha Putra Tiara            | 222313433 |
