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

Bagian ini mendeskripsikan secara rinci fitur-fitur yang ada, baik di *Frontend* (Halaman Publik) maupun di *Backend* (Panel Admin), serta data apa saja yang ditarik dari *database*.

### A. Fitur Halaman Beranda (Home Page - Publik)
Halaman depan portal organisasi (`HomeController@index`).
*   **Data Statistik:** Menampilkan jumlah Anggota terdaftar dan Departemen yang aktif yang dihitung secara *real-time* dari *database*.
*   **Berita Terbaru:** Menarik 3 data tabel `news` terbaru yang berstatus *published*.
*   **Kalender Acara (Event):** Menarik 3 data `events` yang berstatus *upcoming* (akan datang) atau *ongoing* (sedang berlangsung).
*   **Apresiasi & Penghargaan:** 
    *   **Pengurus Terbaik (Best Officers):** Menarik 3 data dari tabel `best_officers` bulan ini (ditambah riwayat bulan lalu jika data baru sudah melebihi 30 hari). Menampilkan nama, departemen, dan alasan penghargaan.
    *   **Ulang Tahun (Birthdays):** Menarik data dari tabel `birthdays` dan mencocokkannya dengan bulan/tanggal server saat ini. Jika ada anggota yang berulang tahun hari ini, akan dimunculkan dengan ucapan selamat (kolom `message`).
*   **Arsip Dokumentasi:** Menarik data `events` yang berstatus *completed* beserta foto unggulan.

### B. Fitur Halaman Profil Departemen (Publik)
Halaman rincian biro/departemen (`DepartemenController@show`).
*   **Identitas & Dokumentasi:** Mengambil `image`, `description`, serta dua gambar polaroid dokumentasi (`doc_image_1`, `doc_image_2`) dari tabel `departments`.
*   **Struktur Organisasi & Tim Kami:** 
    *   Sistem mem-filter anggota (`members`) berdasarkan kabinet yang *is_active* = 1.
    *   Sistem membaca kolom `position` (jabatan) untuk menyusun pohon struktur (Ketua di atas, staf di bawah).
    *   **Sorotan Pengurus (Spotlight 3D):** Menggunakan foto transparan (`photo_nobg`) hasil potongan AI.
    *   **Tim Kami:** Menggunakan pas foto asli dengan background (`photo`).
*   **Program Kerja (Proker):** Me-*looping* data dari tabel `programs` yang terelasi dengan departemen tersebut.

### C. Fitur Katalog Acara & Sistem Ulasan (Publik)
Menampilkan rincian acara secara mandiri.
*   **Detail Acara:** Menampilkan data dari tabel `events` (tanggal, penyelenggara, target peserta).
*   **Galeri & Panitia:** Menarik data relasi `event_documentations` (kumpulan foto) dan `event_committees` (daftar kepanitiaan).
*   **Sistem Rating:** Jika acara sudah selesai (*completed*), pengunjung bisa mengisi *form* ulasan. Data disimpan ke `event_ratings` dan dimunculkan sebagai kolom bintang di halaman acara.

### D. Panel Admin (Dashboard & Backend)
Akses tertutup khusus pengurus.
*   **Manajemen Departemen & Proker:** Admin bisa menambah departemen baru. Di halaman *create/edit*, tersedia form dinamis dengan tombol "+ Tambah Proker" untuk mengisi banyak program kerja sekaligus.
*   **Upload Anggota & Auto-Remove BG:** Form input untuk menambah orang. Saat disimpan, gambar dipotong otomatis oleh AI dan disimpan di server. Alur detailnya dibahas di Bab 6.
*   **Riwayat Kabinet (Cabinet Management):** Memungkinkan admin mendefinisikan tahun periode (misal 2026/2027). Pengurus lama tidak perlu dihapus; admin hanya perlu membuat Kabinet baru dan mematikannya (nonaktifkan `is_active` kabinet lama). Otomatis seluruh halaman publik akan kosong bersiap diisi struktur orang-orang di kabinet baru.
*   **Push Notifikasi Manual:** Pada halaman utama dasbor admin, terdapat tombol "Kirim Notifikasi". Jika diklik, sistem mengeksekusi `NotificationController` untuk mem-blast email pengingat *event* ke semua admin yang terdaftar.

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
