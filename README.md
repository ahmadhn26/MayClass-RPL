# MayClass: Sistem Informasi Manajemen Bimbingan Belajar

MayClass adalah sistem informasi bimbingan belajar berbasis web yang dikembangkan untuk mendigitalisasi dan mengintegrasikan seluruh proses operasional pada Bimbel MayClass.

Proyek ini dikembangkan oleh Tim 1 (Kelas 3SD2) sebagai bagian dari implementasi dan pemenuhan tugas mata kuliah **Rekayasa Perangkat Lunak** di Politeknik Statistika STIS.

## Daftar Isi
- [Latar Belakang Masalah](#latar-belakang-masalah)
- [Solusi Pengembangan](#solusi-pengembangan)
- [Fitur dan Hak Akses Pengguna](#fitur-dan-hak-akses-pengguna)
- [Arsitektur & Spesifikasi Teknologi](#arsitektur--spesifikasi-teknologi)
- [Desain & Wireframe](#desain--wireframe)
- [Instalasi dan Menjalankan Proyek](#instalasi-dan-menjalankan-proyek)
- [Troubleshooting](#troubleshooting)
- [Tim Developer](#tim-developer-kelompok-1---3sd2)
- [Lisensi](#lisensi)

## Latar Belakang Masalah

Saat ini, Bimbel MayClass menjalankan operasional bisnis secara konvensional. Informasi akademik seperti jadwal, materi ajar, dan data siswa masih dikelola secara manual dan tersebar dalam format dokumen fisik atau penyimpanan awan yang tidak terintegrasi. Kondisi ini mengakibatkan inefisiensi dalam manajemen data, kesulitan akses informasi bagi siswa maupun orang tua, serta hambatan dalam mendokumentasikan progres belajar siswa secara terstruktur.

## Solusi Pengembangan

Sistem MayClass dirancang sebagai platform terpadu yang berfungsi sebagai:

1. **Pusat Informasi Terintegrasi:** Menyediakan kanal informasi resmi mengenai profil institusi, katalog paket bimbingan, jadwal akademik, dan portofolio tenaga pengajar.
2. **Manajemen Akademik:** Mengelola basis data siswa, tentor, materi pembelajaran, serta bank soal secara terstruktur dan digital.
3. **Platform E-Learning:** Memfasilitasi kegiatan belajar mengajar secara daring, mencakup akses materi, pengerjaan evaluasi (kuis/tugas), serta pemantauan progres belajar siswa.
4. **Media Branding:** Meningkatkan kredibilitas institusi melalui publikasi testimoni dan dokumentasi kegiatan pembelajaran untuk membangun kepercayaan calon pendaftar.

## Ruang Lingkup & Batasan Sistem
- Sistem difokuskan untuk kebutuhan internal Bimbel MayClass.
- Belum mendukung integrasi payment gateway otomatis.
- Notifikasi sistem masih terbatas pada dashboard aplikasi.
- Keamanan sistem disesuaikan untuk lingkungan akademik, bukan penggunaan komersial berskala besar.

## Fitur dan Hak Akses Pengguna

Sistem ini diklasifikasikan ke dalam empat peran pengguna utama dengan hak akses sebagai berikut:

* **Pengunjung (Publik)**
  * Mengakses informasi umum mengenai profil bimbingan belajar, paket belajar, dan profil tentor.
  * Melihat informasi kontak dan ulasan (testimoni).
  * Melakukan proses registrasi akun siswa baru.

* **Siswa**
  * Melakukan autentikasi (login) ke dalam sistem.
  * Mengakses repositori materi pembelajaran dan bank soal.
  * Mengerjakan evaluasi pembelajaran (tugas atau kuis) secara daring.
  * Memantau riwayat dan progres hasil belajar.

* **Tentor**
  * Melakukan autentikasi (login) ke dalam sistem.
  * Mengelola konten akademik (mengunggah, memperbarui, dan menghapus materi serta bank soal).

* **Admin**
  * Mengelola keseluruhan data pengguna (Siswa, Tentor, dan Admin lainnya).
  * Mengelola aspek administrasi keuangan, termasuk manajemen tagihan dan verifikasi pembayaran siswa.

## Arsitektur & Spesifikasi Teknologi

Sistem ini dibangun menggunakan arsitektur Monolith modern yang memisahkan *concern* antara logika backend dan antarmuka frontend dalam satu repositori terpadu.

* **Backend Framework:** Laravel (PHP)
  * Menggunakan fitur *Security*, *Authentication*, dan *Eloquent ORM* standar industri.
* **Frontend Framework:** Vue.js (terintegrasi dalam Blade Templates)
  * Memberikan interaktivitas dinamis pada komponen antarmuka pengguna.
* **Styling:** Tailwind CSS
  * Utility-first CSS framework untuk desain yang responsif dan konsisten.
* **Database:** MySQL
  * Penyimpanan data relasional untuk integritas data yang tinggi.

## Desain & Wireframe

Rancangan antarmuka (UI/UX) dan prototipe interaktif untuk proyek ini dibuat menggunakan Figma.

* **Tautan Utama Desain:** (https://www.figma.com/design/FYcvU8p4W8qNuyghlN4VFk/WIREFRAME?node-id=134-348&t=359dHXDoq8JX47xF-1)
* **Prototipe Alur Siswa:** (https://www.figma.com/proto/FYcvU8p4W8qNuyghlN4VFk/WIREFRAME?node-id-1-188&p=f&t=UEcMAvLIo68NX2km-1&scaling-min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A188)
* **Prototipe Alur Tentor:** (https://www.figma.com/proto/FYcvU8p4W8qNuyghlN4VFk/WIREFRAME?node-id=134-1161&p=f&t=2VXB)

## Instalasi dan Menjalankan Proyek

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

## Troubleshooting

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

## Tim Developer (Kelompok 1 - 3SD2)

Proyek ini dikerjakan oleh:

| No | Nama                     | NIM        | Peran Utama              |
|----|--------------------------|------------|--------------------------|
| 1  | Xavier Yubin Raditio     | 222313427  | Project Manager & Fullstack Dev |
| 2  | Ahmad Husein Nasution    | 222312952  | Backend Engineer         |
| 3  | Henny Merry Astutik      | 222313120  | UI/UX Designer           |
| 4  | Johana Putri Natasya S.  | 222313150  | Frontend Developer       |
| 5  | Lisa Fajrianti           | 222313174  | System Analyst           |
| 6  | Triangga Hafid Rifa'i    | 222313408  | Database Administrator   |
| 7  | Yudha Putra Tiara        | 222313433  | DevOps & Testing         |

## Lisensi
Proyek ini dikembangkan untuk keperluan akademik di Politeknik Statistika STIS.

Hak cipta © 2025 Tim 1 – MayClass.  
Setiap penggunaan, modifikasi, atau distribusi kode sumber wajib mencantumkan atribusi yang sesuai.

---

**Punya pertanyaan atau ingin berdiskusi lebih lanjut?**

Hubungi kami melalui:
* 📧 **Email:** [222313427@stis.ac.id](mailto:222313427@stis.ac.id)
* 💬 **WhatsApp:** [+62 812-8112-3487](https://wa.me/6281281123487)
