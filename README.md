# 📑 DOKUMEN SPESIFIKASI & PANDUAN PENGEMBANG (SRS-Like Documentation)
**Sistem Informasi Akademik & Dokumentasi FORMAT-R UNESA**

Dokumen ini disusun menyerupai *Software Requirements Specification* (SRS) sederhana yang memuat penjelasan teknis mendalam mengenai struktur kode, database, alur kerja (flow), hingga panduan pengembangan. Dokumen ini dibuat agar **Pengurus/Developer selanjutnya** dapat memahami jeroan sistem tanpa harus menebak-nebak alur kode yang ditinggalkan.

---

## 📋 DAFTAR ISI
1. [Pendahuluan & Konsep Sistem](#1-pendahuluan--konsep-sistem)
2. [Detail Fitur, Data, & Alur (SRS Features)](#2-detail-fitur-data--alur-srs-features)
3. [Stack Teknologi & Kebutuhan (Tech Stack)](#3-stack-teknologi--kebutuhan-tech-stack)
4. [Struktur Folder Utama](#4-struktur-folder-utama)
5. [Skema & Relasi Database (ERD Deskriptif)](#5-skema--relasi-database-erd-deskriptif)
6. [Alur Kerja Sistem (System Flow)](#6-alur-kerja-sistem-system-flow)
7. [Fitur-Fitur Khusus & Kustomisasi](#7-fitur-fitur-khusus--kustomisasi)
8. [Panduan Instalasi & Pengembangan Lokal](#8-panduan-instalasi--pengembangan-lokal)
9. [Deployment ke VPS](#9-deployment-ke-vps)

---

## 1. Pendahuluan & Konsep Sistem

Sistem ini adalah portal profil organisasi yang interaktif. Alih-alih hanya menampilkan halaman statis, sistem ini dibangun secara **Data-Driven**. Artinya, penambahan divisi, anggota pengurus, program kerja, berita, dan *event* dilakukan seluruhnya melalui Panel Admin dan akan langsung mengubah tampilan *Frontend* (Halaman Publik).

Sistem ini dirancang untuk **Estetika Otomatis**, di mana unggahan foto pengguna akan diproses oleh Kecerdasan Buatan (AI) secara mandiri untuk menghapus latar belakang (*background*), merapikan potongan *trim*, dan menerapkan tata letak CSS 3D *Pop-Out* di halaman utama departemen.

---

## 2. Detail Fitur, Data, & Alur (SRS Features)

Sistem ini dibagi menjadi dua modul utama: **A. Modul Frontend (Halaman Publik)** dan **B. Modul Backend (Panel Admin)**. Sistem berjalan secara dinamis di mana Modul A sepenuhnya digerakkan oleh data yang diinput dari Modul B.

### A. MODUL FRONTEND (Halaman Publik)
Modul ini diakses oleh pengunjung umum tanpa perlu login.

**1. Halaman Beranda (`HomeController@index`)**
- **Data Statistik:** Menghitung total `departments` dan `members` secara *real-time*.
- **Berita Terbaru:** Menarik 3 data dari tabel `news` dengan urutan terbaru (`latest`) yang memiliki status `published`.
- **Kalender Acara (Event):** Menarik 3 data dari tabel `events` yang statusnya `upcoming` (akan datang) atau `ongoing` (berlangsung), diurutkan berdasarkan `start_date`.
- **Apresiasi (Pengurus Terbaik & Ulang Tahun):**
  - Menarik data `best_officers` bulan ini, berelasi dengan tabel `members` dan `departments`.
  - Menarik data `birthdays` yang `birth_date`-nya cocok dengan tanggal server hari ini.
- **Arsip Singkat:** Mengambil cuplikan 4 `events` berstatus `completed`.

**2. Halaman Departemen (`DepartemenController@show`)**
- **Profil & Dokumentasi:** Menarik data `name`, `description`, `image` (logo), dan `doc_image_1 & 2` dari tabel `departments`.
- **Sorotan Pengurus (Spotlight 3D):** 
  - Data ditarik dari tabel `members` yang jabatannya mengandung kata kunci pimpinan (BPH/Ketua/Wakil). 
  - Data ini secara eksklusif menggunakan foto `photo_nobg` (hasil remove background AI).
- **Tim Kami (Anggota):** 
  - Menarik sisa data staf dari `members`.
  - Menggunakan foto asli (`photo`) yang memiliki background.
  - **Filter Kritis:** Semua anggota yang dirender di sini difilter otomatis berdasarkan relasi `cabinet_id` yang statusnya `is_active = true` pada tabel `cabinets`.
- **Program Kerja:** Me-looping data dinamis dari tabel `programs` milik departemen terkait.

**3. Halaman Berita (`BeritaController@show`)**
- **Baca Artikel:** Menampilkan konten dari tabel `news` (`title`, `content`, `image`, `published_at`).
- **Rekomendasi (Sidebar):** Menarik berita lain dengan pengurutan `latest` (terbaru), `weekly` (minggu ini), dan `random` (acak) untuk meningkatkan engagement pembaca.

**4. Halaman Event & Sistem Ulasan (`EventController@show` & `rate`)**
- **Detail Acara:** Membaca data `events` (penyelenggara, lokasi, deskripsi).
- **Panitia & Galeri:** Melakukan query relasi ke tabel `event_committees` (daftar panitia) dan `event_documentations` (kumpulan foto galeri acara).
- **Rating/Ulasan (Interaktif):** Jika event berstatus `completed`, pengunjung dapat memberikan rating (1-5). Sistem akan menyimpannya ke tabel `event_ratings` beserta IP Address pengunjung untuk mencegah spam rating ganda.

### B. MODUL BACKEND (Panel Admin)
Modul ini hanya dapat diakses melalui rute `/admin` dan dijaga oleh sistem autentikasi (Middleware Auth).

**1. Manajemen Departemen (`DepartmentController`)**
- **Fitur:** Menambah, mengedit, dan menghapus biro/departemen.
- **Manajemen Proker (Dynamic Input):** Di dalam form departemen, admin dapat menambah kolom program kerja berkali-kali menggunakan tombol "+ Tambah Proker" (Alpine.js). Data disimpan secara looping ke tabel `programs`.

**2. Manajemen Anggota & AI Integration (`MemberController`)**
- **Fitur:** Pendaftaran staf/pengurus ke departemen tertentu.
- **Alur Data & AI:** Saat admin mengunggah foto, foto asli disimpan di tabel `members` kolom `photo`. Sistem lalu memicu Background Job (`ProcessMemberPhotoBackground`) yang memerintahkan Python (`rembg`) untuk menghapus background, memotong pinggir kosong (auto-trim), memotong rasio jika foto berupa full-body (batas pinggang), lalu menyimpannya di kolom `photo_nobg`.

**3. Manajemen Arsip Kabinet (`CabinetController`)**
- **Fitur:** Sistem regenerasi pengurus tahunan tanpa menghapus database lama.
- **Alur Data:** Admin membuat nama kabinet baru (misal "Kolaborasi Asa 2026"), lalu mengaktifkan tombol `is_active`. Sistem akan mematikan kabinet tahun sebelumnya. Halaman departemen publik akan otomatis membaca pengaturan ini dan hanya menampilkan anggota dari kabinet yang sedang aktif.

**4. Manajemen Event (`Admin\EventController`)**
- **Fitur:** Mengontrol status acara (`upcoming`, `ongoing`, `completed`).
- **Manajemen Relasi:** Dalam satu form, admin dapat mengunggah banyak foto panitia (`event_committees`) dan banyak foto dokumentasi (`event_documentations`) sekaligus.

**5. Manajemen Apresiasi (`PenghargaanController` & `UltahController`)**
- **Fitur:** Menentukan siapa 'Best Officer' bulan ini dengan mencari nama dari tabel `members` dan menyematkan deskripsi/alasan kemenangannya. Mengelola daftar ulang tahun dan pesan ucapannya.

**6. Manajemen Berita (`NewsController`)**
- **Fitur:** Menulis rilis pers/berita dengan sistem status (`draft` / `published`).

**7. Sistem Notifikasi Email Manual & Otomatis (`NotificationController` & `Console/Commands`)**
- **Manual:** Admin dapat menekan tombol Kirim Notifikasi di Dashboard. Sistem akan membaca acara yang overdue (lewat batas waktu) atau acara 7 hari ke depan, lalu mem-blast email pengingat kepada seluruh user di tabel `users`.
- **Otomatis:** Task Scheduler berjalan via Linux Cron Job setiap jam 08:00 pagi mengeksekusi logika yang sama persis tanpa campur tangan admin.

**8. Pengaturan Web (`SettingController`)**
- **Fitur:** Mengubah properti global website (Nama Organisasi, Link Sosial Media, Logo) yang disimpan dalam format key-value di tabel `settings`.

**9. Manajemen Akses (`UserController`)**
- **Fitur:** Membuat akun admin baru. Dilengkapi pembatasan akses (Gate/Middleware), di mana hanya akun dengan Role Superadmin yang bisa menghapus atau menambah akun admin lainnya.

---

## 3. Stack Teknologi & Kebutuhan (Tech Stack)

- **Bahasa Pemrograman Utama:** PHP 8.3 & JavaScript
- **Framework Utama:** Laravel (v11/13)
- **Frontend / Styling:** Tailwind CSS v4, Alpine.js, Blade Templating
- **Database:** MySQL / MariaDB
- **Pemrosesan Asinkronus (Queue):** Database Queue bawaan Laravel
- **Artificial Intelligence (AI):** Python 3.10+ dengan library `rembg[cpu]` (untuk Computer Vision / Background Removal).
- **Environment:** Node.js (untuk kompilasi *Vite/Tailwind*), Composer.

---

## 4. Struktur Folder Utama

Proyek ini mengikuti pola standar arsitektur Laravel (MVC - *Model View Controller*), dengan beberapa direktori kustom yang wajib diperhatikan:

```text
📁 FORMAT-R UNESA/
├── 📁 app/
│   ├── 📁 Console/
│   │   └── 📁 Commands/
│   │       └── 📄 SendEventNotifications.php ➔ (Sistem Pengingat) Skrip untuk memindai jadwal acara dari database lalu mengirimkan notifikasi peringatan via email ke seluruh pengguna.
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   ├── 📁 Admin/            ➔ Kumpulan logika backend/dashboard panel admin.
│   │   │   │   ├── 📄 CabinetController.php ➔ Mengelola pergantian kabinet dan mengaktifkan/nonaktifkan status periode (Arsip).
│   │   │   │   ├── 📄 DashboardController.php ➔ Mengelola ringkasan statistik yang tampil di halaman depan dashboard admin.
│   │   │   │   ├── 📄 DepartmentController.php ➔ Mengurus aksi Tambah, Edit, Hapus departemen dan Program Kerjanya.
│   │   │   │   ├── 📄 EventController.php ➔ Mengurus pembuatan acara, panitia acara, galeri dokumentasi, dan review pengunjung.
│   │   │   │   ├── 📄 MemberController.php ➔ Menangani unggahan data anggota. Controller ini yang memerintahkan Python untuk memotong foto (AI).
│   │   │   │   ├── 📄 NewsController.php ➔ Mengelola penulisan, draft, dan unggahan artikel berita.
│   │   │   │   ├── 📄 NotificationController.php ➔ Mengatur fitur "Kirim Notifikasi Manual" ke email semua member.
│   │   │   │   ├── 📄 PenghargaanController.php ➔ Mengelola daftar "Pengurus Terbaik" setiap bulannya.
│   │   │   │   ├── 📄 SettingController.php ➔ Menangani pembaruan pengaturan nama web, sosial media, dsb.
│   │   │   │   ├── 📄 UltahController.php ➔ Mengatur daftar anggota yang berulang tahun.
│   │   │   │   └── 📄 UserController.php ➔ Manajemen hak akses (Role) administrator sistem.
│   │   │   ├── 📁 Auth/             ➔ Mengurusi logika login, register, forgot password, & reset password admin.
│   │   │   ├── 📄 BeritaController.php ➔ Controller publik pembaca artikel berita (`/berita`).
│   │   │   ├── 📄 DepartemenController.php ➔ Menyatukan data anggota dengan data program kerja, merangkai *array* untuk halaman profil departemen publik.
│   │   │   ├── 📄 EventController.php ➔ Memunculkan halaman kalender & rincian acara ke pengunjung umum.
│   │   │   └── 📄 HomeController.php ➔ Otak dari halaman beranda (Landing Page). Mengambil data statistik, berita terbaru, penghargaan terkini, ultah bulan ini, dll.
│   │   └── 📁 Middleware/           ➔ Lapisan filter keamanan (misal: verifikasi apakah user sudah login sebelum bisa masuk ke /admin).
│   ├── 📁 Jobs/
│   │   └── 📄 ProcessMemberPhotoBackground.php ➔ (Sistem Pekerja). File krusial tempat "Antrean / Queue" dikerjakan. Tugasnya menerima perintah dari MemberController untuk memotong background tanpa mengganggu loading website.
│   ├── 📁 Mail/
│   │   └── 📄 EventNotificationMail.php ➔ Menyambungkan data dari sistem ke tampilan HTML template Email sebelum di-blast.
│   ├── 📁 Models/                   ➔ Definisi struktur tabel Database (ORM Eloquent).
│   │   ├── 📄 BestOfficer.php (Penghargaan bulanan)
│   │   ├── 📄 Birthday.php (Data Ultah anggota)
│   │   ├── 📄 Cabinet.php (Periode kepengurusan)
│   │   ├── 📄 Department.php (Data Induk Departemen)
│   │   ├── 📄 Event.php, EventCommittee.php, EventDocumentation.php, EventRating.php (Ekosistem Acara)
│   │   ├── 📄 Member.php (Anggota organisasi)
│   │   ├── 📄 News.php (Artikel Berita)
│   │   ├── 📄 Program.php (Program Kerja)
│   │   ├── 📄 Setting.php (Pengaturan global situs)
│   │   └── 📄 User.php (Admin)
│   └── 📁 Traits/
│       └── 📄 ImageUploadTrait.php  ➔ (Alat Eksekusi). Tempat di mana fungsi `autoCropTransparent()` (memotong ruang sisa) dan `removeBackgroundAndSaveWebp()` (eksekusi python `rembg`) ditulis secara sentral agar bisa dipanggil berulang kali.
├── 📁 database/
│   ├── 📁 migrations/               ➔ Catatan sejarah dan skema kolom tabel database.
│   └── 📁 seeders/                  ➔ Skrip untuk mengisi database dengan data dummy awal (admin pertama).
├── 📁 public/
│   ├── 📁 build/                    ➔ File CSS dan JS statis hasil compile Vite (TailwindCSS). Jangan diedit secara manual.
│   ├── 📁 images/                   ➔ Aset gambar statis bawaan sistem (seperti logo web/dummy foto).
│   └── 📁 storage/                  ➔ Symlink (jalan pintas) rahasia yang menghubungkan ke folder `storage/app/public` sehingga file upload dari admin bisa diakses publik secara aman.
├── 📁 resources/
│   └── 📁 views/                    ➔ Folder Induk UI / Tampilan HTML Front-end (Templating Blade).
│       ├── 📁 admin/                ➔ (Modul Backend) Kumpulan tampilan Panel Dashboard Admin.
│       │   ├── 📁 auth/             ➔ Halaman login, register, dan lupa password admin.
│       │   ├── 📁 awards/           ➔ Form dan tabel data "Pengurus Terbaik".
│       │   ├── 📁 birthdays/        ➔ Form dan tabel data "Ulang Tahun".
│       │   ├── 📁 cabinets/         ➔ Pengaturan arsip periode kabinet.
│       │   ├── 📁 dashboard/        ➔ Halaman utama setelah admin login (statistik admin).
│       │   ├── 📁 departments/      ➔ Form dinamis input/edit departemen dan program kerja.
│       │   ├── 📁 events/           ➔ Pengelolaan agenda, kepanitiaan, dan galeri acara.
│       │   ├── 📁 layouts/          ➔ Template induk untuk antarmuka backend/admin (navbar & sidebar).
│       │   ├── 📁 news/             ➔ Editor teks dan unggahan artikel berita.
│       │   ├── 📁 settings/         ➔ Halaman konfigurasi identitas website.
│       │   └── 📁 users/            ➔ Pengaturan akun *role* admin.
│       ├── 📁 berita/
│       │   └── 📄 show.blade.php    ➔ Tampilan publik saat seseorang sedang membaca artikel berita.
│       ├── 📁 components/
│       │   └── 📄 opening.blade.php ➔ Komponen (potongan HTML) *reusable* untuk memunculkan efek animasi/transisi saat halaman baru saja dibuka.
│       ├── 📁 departemen/           ➔ Tampilan direktori departemen publik.
│       │   ├── 📄 index.blade.php   ➔ Halaman "Buku Direktori" yang melisting *card* dari semua departemen.
│       │   ├── 📄 show.blade.php    ➔ Pembungkus (*wrapper*) untuk merender tema spesifik dari suatu departemen.
│       │   └── 📁 themes/
│       │       └── 📄 klasik.blade.php ➔ (Inti Tampilan). Template khusus halaman detail departemen yang memuat desain kartu "Sorotan Pengurus 3D Pop-Out", pemanggilan foto_nobg, serta algoritma perulangan ornamen (*SVG floating*) secara *random*.
│       ├── 📁 emails/
│       │   └── 📁 events/
│       │       └── 📄 notification.blade.php ➔ Kerangka HTML visual untuk email otomatis yang dikirim ke Inbox/Gmail admin/anggota (Cron Job).
│       ├── 📁 event/                ➔ (Modul Frontend) Tampilan agenda organisasi publik.
│       │   ├── 📄 index.blade.php   ➔ Halaman katalog/kalender daftar seluruh acara.
│       │   └── 📄 show.blade.php    ➔ Halaman rincian lengkap sebuah acara, menampilkan jadwal, foto galeri, panitia, serta *form popup* (bintang 1-5) untuk memberikan ulasan acara.
│       ├── 📁 home/
│       │   └── 📄 index.blade.php   ➔ Otak tampilan "Beranda Utama / Landing Page". Memuat blok statistik, berita sekilas, pengurus terbaik bulan ini, dan FAQ.
│       ├── 📁 layouts/              ➔ (Modul Frontend) Template kerangka induk halaman publik.
│       │   ├── 📄 app.blade.php     ➔ Template induk utama (Header & Footer standar web).
│       │   └── 📄 dept-base.blade.php ➔ Template khusus untuk mengakomodir gaya desain halaman departemen agar lebih *immersive* tanpa *navbar* biasa.
│       ├── 📄 arsip.blade.php       ➔ Halaman publik khusus memajang kumpulan arsip (event/berkas) yang sudah berlalu.
│       └── 📄 welcome.blade.php     ➔ *Fallback* halaman selamat datang (opsional).
├── 📁 routes/
│   ├── 📄 web.php                   ➔ Peta jalan website (Routing). Pendaftar semua URL (misal: /admin/login).
│   └── 📄 console.php               ➔ Peta waktu. Mendaftarkan jadwal (scheduler) "jam 8 pagi jalankan SendEventNotifications.php".
├── 📄 .env                          ➔ (TIDAK ADA DI GITHUB) File rahasia tempat menyimpan kredensial Database, Username Email SMTP, dan Kunci Keamanan Laravel.
└── 📄 MIGRASI_VPS.md                ➔ Panduan spesifik cara hosting website ini ke server (VPS).
```

---

## 5. Skema & Relasi Database (ERD Deskriptif)

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

## 6. Alur Kerja Sistem (System Flow)

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

## 7. Fitur-Fitur Khusus & Kustomisasi

Jika Anda (pengembang selanjutnya) ingin memodifikasi tampilan *Frontend*, perhatikan file `resources/views/departemen/themes/klasik.blade.php`:

- **Distribusi Ornamen (Hiasan):** 
  Di dalam file ini, terdapat logika *looping* PHP `@for` yang secara otomatis menggandakan dan menyebarkan ornamen SVG. Ada konfigurasi acak (`rand()`) untuk menyebarkan posisi X dan Y, skala, dan rotasinya. Aturan ini terbagi dua: sebaran khusus *Hero area* (paling atas) dan *Content area* (menjuntai ke bawah).
- **Penyesuaian Skala Pemotongan Kaki (Auto-Trim):**
  Jika suatu saat Anda merasa potongan pinggang foto di web terlalu pendek atau terlalu panjang, carilah `ImageUploadTrait.php` baris ke `180` (komentar: `// --- STANDARISASI RASIO UNTUK MEMBUANG KAKI (FULL BODY) ---`). 
  Ubah nilai pengali rasio `$idealHeight = (int)($newWidth * 1.5);` menjadi `1.2` (untuk potongan dada) atau `2.0` (untuk mempertahankan foto selutut).

---

## 8. Panduan Instalasi & Pengembangan Lokal

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

## 9. Deployment ke VPS (Produksi Asli)

Sistem ini **TIDAK MENDUKUNG** cPanel / Shared Hosting biasa. Alasannya jelas: Anda tidak memiliki akses terminal root di Shared Hosting untuk menginstal Python (`rembg`), mengunci Supervisor 24 jam, maupun mengaktifkan Cron Job tingkat lanjut.

Website ini **WAJIB** di-hosting di **Virtual Private Server (VPS)**.

Untuk panduan mendirikan VPS dari 0 (kosong) hingga website *live* sempurna, silakan baca dokumentasi pendamping yang telah dibuat:
👉 **[Buka Panduan MIGRASI VPS (MIGRASI_VPS.md)](./MIGRASI_VPS.md)**

---

*Terima kasih! Dokumentasi ini dibuat untuk menjaga keberlanjutan regenerasi divisi teknologi (IT) di FORMAT-R UNESA.*
