# 📑 DOKUMEN SPESIFIKASI & PANDUAN PENGEMBANG (SRS-Like Documentation)
**Sistem Informasi Akademik & Dokumentasi FORMAT-R UNESA**

Dokumen ini disusun menyerupai *Software Requirements Specification* (SRS) sederhana yang memuat penjelasan teknis mendalam mengenai struktur kode, database, alur kerja (flow), hingga panduan pengembangan. Dokumen ini dibuat agar **Pengurus/Developer selanjutnya** dapat memahami jeroan sistem tanpa harus menebak-nebak alur kode yang ditinggalkan.

---

## 📋 DAFTAR ISI
1. [Pendahuluan & Konsep Sistem](#1-pendahuluan--konsep-sistem)
2. [Stack Teknologi & Kebutuhan (Tech Stack)](#2-stack-teknologi--kebutuhan)
3. [Struktur Folder Utama](#3-struktur-folder-utama)
4. [Skema & Relasi Database (ERD Deskriptif)](#4-skema--relasi-database)
5. [Alur Kerja Sistem (System Flow)](#5-alur-kerja-sistem-system-flow)
6. [Fitur-Fitur Khusus & Kustomisasi](#6-fitur-fitur-khusus--kustomisasi)
7. [Panduan Instalasi & Pengembangan Lokal](#7-panduan-instalasi--pengembangan-lokal)
8. [Deployment ke VPS](#8-deployment-ke-vps)

---

## 1. Pendahuluan & Konsep Sistem

Sistem ini adalah portal profil organisasi yang interaktif. Alih-alih hanya menampilkan halaman statis, sistem ini dibangun secara **Data-Driven**. Artinya, penambahan divisi, anggota pengurus, program kerja, berita, dan *event* dilakukan seluruhnya melalui Panel Admin dan akan langsung mengubah tampilan *Frontend* (Halaman Publik).

Sistem ini dirancang untuk **Estetika Otomatis**, di mana unggahan foto pengguna akan diproses oleh Kecerdasan Buatan (AI) secara mandiri untuk menghapus latar belakang (*background*), merapikan potongan *trim*, dan menerapkan tata letak CSS 3D *Pop-Out* di halaman utama departemen.

---

## 2. Stack Teknologi & Kebutuhan

- **Bahasa Pemrograman Utama:** PHP 8.3 & JavaScript
- **Framework Utama:** Laravel (v11/13)
- **Frontend / Styling:** Tailwind CSS v4, Alpine.js, Blade Templating
- **Database:** MySQL / MariaDB
- **Pemrosesan Asinkronus (Queue):** Database Queue bawaan Laravel
- **Artificial Intelligence (AI):** Python 3.10+ dengan library `rembg[cpu]` (untuk Computer Vision / Background Removal).
- **Environment:** Node.js (untuk kompilasi *Vite/Tailwind*), Composer.

---

## 3. Struktur Folder Utama

Proyek ini mengikuti pola standar arsitektur Laravel (MVC - *Model View Controller*), dengan beberapa direktori kustom yang wajib diperhatikan:

```text
📁 FORMAT-R UNESA/
├── 📁 app/
│   ├── 📁 Console/
│   │   └── 📁 Commands/
│   │       └── 📄 SendEventNotifications.php ➔ (Sistem Pengingat) Skrip untuk memindai jadwal acara dari database lalu mengirimkan notifikasi peringatan via email ke seluruh pengguna.
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   ├── 📁 Admin/            ➔ Backend. Berisi controller panel manajemen (seperti CabinetController.php, DepartmentController.php, UserController.php, dll). Mengontrol input, tambah, hapus (CRUD) ke database.
│   │   │   ├── 📁 Auth/             ➔ Mengurusi login, register, forgot password, & reset password admin.
│   │   │   └── 📄 *Controller Publik* ➔ Controller frontend (seperti HomeController.php, DepartemenController.php, BeritaController.php). Mengambil data dari database (Read Only) untuk ditampilkan di halaman publik/pengunjung.
│   │   └── 📁 Middleware/           ➔ Lapisan filter keamanan (misal: verifikasi apakah user sudah login sebelum bisa masuk ke /admin).
│   ├── 📁 Jobs/
│   │   └── 📄 ProcessMemberPhotoBackground.php ➔ (Sistem Pekerja). File krusial tempat "Antrean / Queue" dikerjakan. Tugasnya adalah menerima foto anggota, menunggu tanpa mengganggu halaman website, lalu memerintahkan Python untuk memotong background.
│   ├── 📁 Mail/
│   │   └── 📄 EventNotificationMail.php ➔ Kerangka penyusunan subjek dan struktur badan email otomatis.
│   ├── 📁 Models/
│   │   └── 📄 *.php                 ➔ File representasi Database (ORM Eloquent). Setiap tabel diwakili 1 model (misal Member.php untuk tabel members). Digunakan untuk mendefinisikan relasi antar tabel (seperti 1 departemen punya banyak member).
│   └── 📁 Traits/
│       └── 📄 ImageUploadTrait.php  ➔ (Alat Eksekusi). Tempat di mana fungsi `autoCropTransparent()` (memotong ruang sisa) dan `removeBackgroundAndSaveWebp()` (eksekusi python `rembg`) ditulis secara sentral agar bisa dipakai oleh controller mana saja.
├── 📁 database/
│   ├── 📁 migrations/               ➔ Catatan sejarah database. Setiap ada tambah kolom/tabel baru (misal tabel programs), file PHP ini akan mengatur struktur databasenya.
│   └── 📁 seeders/                  ➔ Skrip untuk mengisi database dengan data dummy awal.
├── 📁 public/
│   ├── 📁 build/                    ➔ File CSS dan JS statis hasil compile Vite (TailwindCSS). Jangan diedit secara manual.
│   ├── 📁 images/                   ➔ Aset gambar statis bawaan sistem (seperti logo web/dummy foto).
│   └── 📁 storage/                  ➔ Symlink (jalan pintas) rahasia yang menghubungkan ke folder `storage/app/public` sehingga file upload dari admin (foto pengurus, berita) bisa diakses publik secara aman.
├── 📁 resources/
│   └── 📁 views/                    ➔ Folder UI / Tampilan HTML (menggunakan sistem templating Blade).
│       ├── 📁 admin/                ➔ Kumpulan tampilan untuk Admin Dashboard, Form Input, dan Tabel Data (Auth, Awards, Departemen, Berita, dll).
│       ├── 📁 departemen/
│       │   └── 📁 themes/
│       │       └── 📄 klasik.blade.php ➔ (Tema Utama). File super penting tempat desain "Sorotan Pengurus", algoritma Pop-Out 3D CSS, dan algoritma *random looping* hiasan ornamen (*svg*) ditulis.
│       ├── 📁 event/, 📁 berita/    ➔ Tampilan halaman baca artikel & rincian acara.
│       └── 📁 emails/               ➔ Tampilan visual dari HTML email yang masuk ke kotak masuk (inbox) Google/Yahoo pengunjung.
├── 📁 routes/
│   ├── 📄 web.php                   ➔ Peta jalan. Jika URL-nya '/departemen', arahkan ke Controller A, jika '/admin/login', arahkan ke Controller B.
│   └── 📄 console.php               ➔ Peta waktu. Mendaftarkan jadwal (scheduler) "jam 8 pagi jalankan SendEventNotifications.php".
├── 📄 .env                          ➔ (TIDAK ADA DI GITHUB) File rahasia tempat menyimpan kredensial Database, Username Email SMTP, dan Kunci Keamanan Laravel.
└── 📄 MIGRASI_VPS.md                ➔ Panduan spesifik cara hosting website ini ke server (VPS).
```

---

## 4. Skema & Relasi Database

Berikut adalah pemetaan seluruh tabel (13 tabel utama) dalam sistem ini (sebagai representasi ERD - *Entity Relationship Diagram*):

### A. Tabel `users` (Pengguna/Admin)
Data otentikasi login admin sistem.
- `id`, `name`, `email`, `password`
- `role` (superadmin/admin)

### B. Tabel `departments` (Departemen)
Menyimpan profil induk departemen/biro.
- `id` (PK)
- `name`, `abbreviation`, `slug`
- `description`, `image` (Logo Utama)
- `doc_image_1`, `doc_image_2` (Foto Dokumentasi polaroid)
- **Relasi:** `1:N` ke `members`, `1:N` ke `programs`

### C. Tabel `members` (Pengurus/Anggota)
Data setiap orang di organisasi.
- `id` (PK)
- `department_id` (FK ke departments)
- `cabinet_id` (FK ke cabinets - untuk membedakan pengurus tahun lalu dan sekarang)
- `name`, `position`
- `photo` (Menyimpan foto asli hasil upload)
- `photo_nobg` (Menyimpan foto transparan hasil tebasan AI Python `rembg`)

### D. Tabel `programs` (Program Kerja)
Program kerja dinamis per departemen.
- `id` (PK)
- `department_id` (FK ke departments)
- `no` (Nomor urut proker, misal "01")
- `name`, `description`

### E. Tabel Manajemen *Events* (Acara)
Menyimpan detail kegiatan, panitia, hingga *feedback* peserta.
1. **`events`** (Tabel Utama)
   - `id`, `title`, `slug`, `description`, `organizer`
   - `status` (upcoming, ongoing, completed)
   - `start_date`, `end_date`
   - `participant_count`
2. **`event_committees`** (Panitia event)
   - FK ke `event_id`. Menyimpan `name`, `role`, dan `photo` panitia.
3. **`event_documentations`** (Dokumentasi event)
   - FK ke `event_id`. Menyimpan kumpulan `photo` pasca-acara.
4. **`event_ratings`** (Ulasan/Rating)
   - FK ke `event_id`. Menyimpan `name_reviewer`, `rating` (1-5 bintang), dan `review`.

### F. Tabel `news` (Berita / Artikel)
Sistem publikasi pers organisasi.
- `id`, `title`, `slug`, `content`
- `image`, `published_at`

### G. Tabel Apresiasi (Penghargaan & Ultah)
Sistem *reward* dan peringatan hari lahir anggota.
1. **`best_officers`** (Pengurus Terbaik)
   - `member_id`, `month`, `year`, `reason`
2. **`birthdays`** (Ulang Tahun)
   - `member_id`, `birth_date`, `message`

### H. Tabel `cabinets` (Kabinet/Periode)
Sistem arsip. Saat ganti tahun, admin cukup membuat kabinet baru tanpa menghapus data departemen yang lama.
- `id`, `name` (Nama kabinet, misal: "Kolaborasi Asa")
- `period` (Tahun, misal: "2026/2027"), `vision`, `mission`, `logo`
- `is_active` (Boolean, hanya boleh 1 yang aktif. Pengurus yang tampil di halaman publik otomatis ter-filter berdasar kabinet ini).

### I. Tabel `settings` (Pengaturan Web)
- Menyimpan konfigurasi dinamis situs yang bisa diubah via Admin Panel.
- `key`, `value`

### J. Tabel Sistem Bawaan (Laravel Core)
Tabel-tabel ini merupakan penggerak utama mesin Laravel (Framework). Secara struktural tidak memiliki *Foreign Key* (relasi) ke tabel bisnis di atas karena dikendalikan langsung oleh *core engine* sistem. **Dilarang keras untuk menghapus tabel ini.**

1. **`cache` & `cache_locks`**
   - **Fungsi:** Menyimpan data memori jangka pendek (*cache*) untuk mempercepat waktu muat (loading) halaman web. Ini mencegah server memproses data yang sama berulang-ulang dari awal.
2. **`jobs`**
   - **Fungsi:** Menampung tugas-tugas antrean (Queue) di belakang layar. Di sistem ini, tabel `jobs` menampung "Perintah Memotong Background Foto via AI" dan "Perintah Mengirim Email Otomatis" agar dikerjakan satu per satu tanpa memberatkan sistem utama.
3. **`failed_jobs` & `job_batches`**
   - **Fungsi:** Menyimpan rekam jejak apabila tugas di belakang layar (dari tabel `jobs` di atas) gagal dieksekusi (misalnya AI error atau email tertolak), sehingga admin atau developer bisa menelusuri penyebab gagalnya tugas tersebut.
4. **`sessions`** *(Jika ada)*
   - **Fungsi:** Menyimpan status login pengguna (sesi), sehingga jika pengunjung me-refresh halaman, mereka tidak tiba-tiba ter-logout.
5. **`migrations`**
   - **Fungsi:** Tabel mutlak milik Laravel untuk mencatat file migrasi database mana saja yang sudah dieksekusi, sehingga menghindarkan duplikasi tabel.

---

## 5. Alur Kerja Sistem (System Flow)

### Flow 1: Upload Foto & Pemotongan Latar Belakang (AI Processing)
Karena AI berat untuk diproses seketika, alurnya menggunakan skema **Asynchronous (Pekerja Latar Belakang)**.

1. **User (Admin)** mengisi form tambah/edit anggota dan mengunggah foto.
2. **Controller (`MemberController@store`)** menyimpan data anggota ke database dan menyimpan *foto asli* ke *storage*.
3. **Queue Dispatch:** Controller memerintahkan Laravel Queue (`ProcessMemberPhotoBackground::dispatch()`) lalu segera mengarahkan (redirect) admin kembali ke halaman sukses tanpa perlu menunggu AI bekerja.
4. **Queue Worker (`php artisan queue:work`)** di terminal/server mengambil tugas tersebut secara diam-diam.
5. **Python (`rembg`) & GD Execution (di `ImageUploadTrait`):**
   - File dibaca, lalu dilempar ke sistem command line: `rembg i foto_asli.jpg foto_temp.png`.
   - AI menyeleksi objek manusia dan menghapus latar belakang.
   - PHP memanggil fungsi kustom `autoCropTransparent()` untuk membuang sisa kanvas kosong agar proporsinya ketat di pinggiran tubuh orang.
   - PHP mengecek rasio gambar. Jika gambar adalah "foto full kaki/badan", PHP memotong bagian kaki hingga sebatas pinggang secara paksa (rasio 1:1.5).
   - PHP mengonversinya ke format `.webp` untuk kompresi maksimal.
6. **Update Database:** Worker selesai, database `members.photo_nobg` diperbarui. Saat di-refresh, halaman web berubah menjadi transparan.

### Flow 2: Rendering UI "Sorotan Pengurus" (Frontend)
1. User publik mengakses halaman departemen.
2. `DepartemenController@show` menarik data departemen, proker, dan mem-filter data anggota *hanya* dari kabinet yang sedang aktif.
3. Di file `klasik.blade.php`, anggota *Board of Directors* (BPH/Ketua/Wakil) diidentifikasi menggunakan fungsi filter *(string contains)* pada jabatannya.
4. Anggota tersebut akan dirender ke `.spotlight-card`. 
5. Sistem me-render `photo_nobg` (hasil AI). Dengan kombinasi CSS `.spotlight-card::before` (tinggi mentok 250px) dan `.spotlight-foto` (tinggi 440px), bagian kepala subjek akan menembus atap card (efek *3D Pop-Out*).

### Flow 3: Notifikasi Email Otomatis (Cron Job Schedule)
1. Di `routes/console.php`, terdapat pendaftaran perintah `notify:events` setiap jam 08:00 pagi.
2. Linux Server (Cron Job) yang disetel berjalan setiap menit (`* * * * *`) akan mendeteksi jadwal ini.
3. Tepat jam 8 pagi, Cron Job menjalankan `App\Console\Commands\SendEventNotifications`.
4. Kode menyeleksi event (Acara yang belum mulai, sedang berjalan tapi lewat waktu, atau H-7).
5. Sistem me-*looping* seluruh pengguna (`users`) di database, dan mengirimkan email melalui konfigurasi SMTP Gmail.

---

## 6. Fitur-Fitur Khusus & Kustomisasi

Jika Anda (pengembang selanjutnya) ingin memodifikasi tampilan *Frontend*, perhatikan file `resources/views/departemen/themes/klasik.blade.php`:

- **Distribusi Ornamen (Hiasan):** 
  Di dalam file ini, terdapat logika *looping* PHP `@for` yang secara otomatis menggandakan dan menyebarkan ornamen SVG. Ada konfigurasi acak (`rand()`) untuk menyebarkan posisi X dan Y, skala, dan rotasinya. Aturan ini terbagi dua: sebaran khusus *Hero area* (paling atas) dan *Content area* (menjuntai ke bawah).
- **Penyesuaian Skala Pemotongan Kaki (Auto-Trim):**
  Jika suatu saat Anda merasa potongan pinggang foto di web terlalu pendek atau terlalu panjang, carilah `ImageUploadTrait.php` baris ke `180` (komentar: `// --- STANDARISASI RASIO UNTUK MEMBUANG KAKI (FULL BODY) ---`). 
  Ubah nilai pengali rasio `$idealHeight = (int)($newWidth * 1.5);` menjadi `1.2` (untuk potongan dada) atau `2.0` (untuk mempertahankan foto selutut).

---

## 7. Panduan Instalasi & Pengembangan Lokal

Bagi Developer baru yang ingin melanjutkan proyek ini di laptop lokal:

1. **Pastikan Komputer Anda Memiliki:** PHP 8.3, Composer, Node.js, MySQL, Python 3.10+, dan PIP.
2. Kloning dan masuk ke folder proyek.
3. Buka terminal 1 (Install Dependencies):
   ```bash
   composer install
   npm install
   npm run build
   ```
4. Salin `.env.example` ke `.env`, atur `DB_DATABASE` (buat database MySQL). Set `QUEUE_CONNECTION=database`.
5. Install Python Modul (Untuk AI lokal):
   ```bash
   pip install "rembg[cpu]"
   ```
6. Setup Database:
   ```bash
   php artisan key:generate
   php artisan migrate
   php artisan storage:link
   ```
7. Jalankan Server:
   ```bash
   php artisan serve
   ```
8. **SANGAT PENTING (Jalankan Worker):**
   Buka terminal ke-2, jalankan perintah ini agar proses *upload* foto dan penghapusan latar belakang bisa jalan saat di-test:
   ```bash
   php artisan queue:work --timeout=1800
   ```

---

## 8. Deployment ke VPS (Produksi Asli)

Sistem ini **TIDAK MENDUKUNG** cPanel / Shared Hosting biasa. Alasannya jelas: Anda tidak memiliki akses terminal root di Shared Hosting untuk menginstal Python (`rembg`), mengunci Supervisor 24 jam, maupun mengaktifkan Cron Job tingkat lanjut.

Website ini **WAJIB** di-hosting di **Virtual Private Server (VPS)**.

Untuk panduan mendirikan VPS dari 0 (kosong) hingga website *live* sempurna, silakan baca dokumentasi pendamping yang telah dibuat:
👉 **[Buka Panduan MIGRASI VPS (MIGRASI_VPS.md)](./MIGRASI_VPS.md)**

---

*Terima kasih! Dokumentasi ini dibuat untuk menjaga keberlanjutan regenerasi divisi teknologi (IT) di FORMAT-R UNESA.*
