# Panduan Migrasi Website FORMAT-R UNESA ke cPanel (Shared Hosting)

Panduan ini dikhususkan bagi tim yang melakukan *deployment* (hosting) menggunakan **cPanel / Shared Hosting**. Karena keterbatasan cPanel yang tidak memiliki akses root/sudo, ada beberapa penyesuaian khusus yang harus dilakukan, terutama terkait fitur AI Remove Background.

---

## 1. Persiapan File di Laptop (Kompresi Zip)

Sebelum mengunggah ke cPanel, Anda perlu menyiapkan *source code* website ini.
1. Pastikan Anda sudah menjalankan perintah build aset frontend: `npm run build` di laptop Anda.
2. Hapus folder `node_modules` (folder ini sangat besar dan tidak dibutuhkan di server produksi).
3. **Pilih seluruh file dan folder** di dalam folder proyek Anda (termasuk folder tersembunyi `.env` jika ada), lalu **Kompres menjadi satu file ZIP** (misalnya `formatr.zip`).

## 2. Pembuatan Database di cPanel

1. Login ke cPanel Anda.
2. Cari menu **"MySQL® Database Wizard"**.
3. **Step 1:** Masukkan nama database (misal: `formatr_db`).
4. **Step 2:** Buat nama User (misal: `formatr_usr`) dan buat Password yang sangat kuat. *Catat password ini!*
5. **Step 3:** Centang kotak **"ALL PRIVILEGES"** untuk memberikan izin penuh pada user database tersebut.

## 3. Unggah (Upload) dan Ekstrak File di cPanel

**A. Upload File ZIP**
1. Di cPanel, buka menu **File Manager**.
2. Masuk ke folder root direktori akun Anda (SANGAT PENTING: letakkan di `/home/username_cpanel/`, **JANGAN** di dalam folder `public_html`).
3. Klik tombol **Upload**, pilih file `formatr.zip` tadi.
4. Setelah selesai, ekstrak file tersebut (klik kanan -> Extract).
5. Ganti nama folder hasil ekstrak menjadi `formatr_core` (atau nama lain yang Anda suka).

**B. Memindahkan Folder Public**
Laravel memiliki sistem keamanan di mana hanya folder `public` yang boleh diakses internet.
1. Masuk ke dalam folder `formatr_core` hasil ekstrak tadi.
2. Cari folder bernama **`public`**.
3. **Pindahkan (Move)** seluruh **isi dari dalam folder public** tersebut ke dalam folder **`public_html`** utama cPanel Anda.

## 4. Penyesuaian Path di `index.php`

Karena file publik dan inti (*core*) Laravel sekarang terpisah, Anda harus mengarahkan ulang kodenya.
1. Masuk ke folder `public_html` (atau folder yang diarahkan oleh domain/subdomain Anda).
2. Edit file `index.php`.
3. Ubah bagian baris `require` menjadi seperti ini (sesuaikan dengan nama folder `formatr_core` tadi):

```php
// Cari baris ini:
require __DIR__.'/../vendor/autoload.php';
// Ubah menjadi (tambahkan ../ jika formatr_core berada 1 tingkat di luar public_html):
require __DIR__.'/../formatr_core/vendor/autoload.php';

// Cari baris ini:
$app = require_once __DIR__.'/../bootstrap/app.php';
// Ubah menjadi:
$app = require_once __DIR__.'/../formatr_core/bootstrap/app.php';
```

*(Catatan Pemasangan Domain: Secara default, domain utama di cPanel selalu membaca isi folder `public_html`. Jika Anda ingin memasang di sub-domain seperti `formatr.namakampus.ac.id`, pastikan Anda membuat sub-domain tersebut terlebih dahulu dari menu cPanel, arahkan "Document Root" sub-domain tersebut ke folder kosong baru, lalu pindahkan isi folder `public` milik Laravel ke folder baru tersebut, dan pindahkan `formatr_core` ke lokasi terpisah yang sejajar).*

## 5. Penyesuaian File Konfigurasi (`.env`) & Setting SMTP (Email)

1. Buka folder `formatr_core`.
2. Edit file `.env`.
3. Sesuaikan dengan pengaturan Database dan URL domain cPanel Anda:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-website-anda.com

DB_DATABASE=prefixcpanel_formatr_db
DB_USERNAME=prefixcpanel_formatr_usr
DB_PASSWORD="PasswordYangDibuatTadi"

# Ubah driver queue agar bisa dijalankan manual via cron
QUEUE_CONNECTION=database
```

**Konfigurasi SMTP (Untuk Fitur Notifikasi Email):**
Untuk mengirim email otomatis (seperti jadwal acara jam 08:00 pagi), Anda perlu memasukkan kredensial email. Sangat disarankan menggunakan akun Gmail khusus.

1. Buka akun Google/Gmail organisasi Anda.
2. Pastikan fitur "Verifikasi 2 Langkah" aktif.
3. Cari menu **Sandi Aplikasi (App Passwords)** di setelan keamanan akun Google.
4. Buat sandi khusus, lalu salin 16 karakter sandi yang diberikan Google.
5. Edit bagian email di dalam `.env` cPanel Anda:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME="email_organisasi@gmail.com"
MAIL_PASSWORD="kode_sandi_aplikasi_16_digit_tanpa_spasi"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="email_organisasi@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```
*(Catatan: Jika port 465 dengan SSL gagal, coba gunakan `MAIL_PORT=587` dengan `MAIL_ENCRYPTION=tls`)*.

## 6. Mengatasi Fitur Auto Remove Background AI di cPanel

Fitur potong background lokal (`rembg`) membutuhkan Python dan akses eksekusi sistem operasi tingkat rendah yang **diblokir oleh cPanel**. 
Oleh karena itu, sistem ini telah dirancang untuk melompat *(fallback)* menggunakan **Clipdrop API (High-Res)** terlebih dahulu, lalu disusul **Remove.bg API (Low-Res)** sebagai pertahanan terakhir.

1. Buat akun di [Clipdrop API](https://clipdrop.co/apis) dan [Remove.bg API](https://www.remove.bg/api).
2. Ambil **API Key** dari masing-masing akun (Bisa mendaftarkan beberapa akun/email untuk menumpuk kuota gratis).
3. Edit file `.env` di dalam cPanel, dan tempelkan kunci API tersebut:
```env
# Prioritas 1: Clipdrop API (Kualitas Tinggi)
CLIPDROP_KEY_1="paste_api_key_clipdrop_1"
CLIPDROP_KEY_2="paste_api_key_clipdrop_2"
CLIPDROP_KEY_3="paste_api_key_clipdrop_3"

# Prioritas 2 (Cadangan): Remove.bg API (Resolusi diturunkan)
REMOVE_BG_KEY_1="paste_api_key_removebg_1"
REMOVE_BG_KEY_2="paste_api_key_removebg_2"
REMOVE_BG_KEY_3="paste_api_key_removebg_3"
```
*Sistem akan otomatis mencoba Clipdrop Key 1. Jika gagal/kredit habis, pindah ke Key 2. Jika semua Clipdrop habis/gagal, baru ia akan menggunakan Remove.bg.*

## 7. Membuat Symlink Storage (Menampilkan Foto)

Foto tidak akan muncul di web jika Anda tidak membuat Symlink. Karena tidak ada akses terminal yang mudah, kita akali dengan *Cron Job* sementara.
1. Di cPanel, buka menu **"Cron Jobs"**.
2. Di bagian "Add New Cron Job", atur Common Settings menjadi "Once a minute".
3. Di kotak "Command", masukkan perintah berikut (Sesuaikan kata `username_cpanel`!):
```bash
ln -s /home/username_cpanel/formatr_core/storage/app/public /home/username_cpanel/public_html/storage
```
4. Klik **Add New Cron Job**.
5. Tunggu maksimal 1 menit. Jika folder `storage` sudah muncul di dalam `public_html` dan foto di web sudah bisa dilihat, **segera hapus Cron Job tersebut** agar tidak berjalan berulang-ulang!

## 8. Konfigurasi Queue Worker (Pekerja Latar Belakang) via Cron

Untuk memproses upload foto AI dan mengirim email, Laravel menggunakan sistem "Antrean" (*Queue*). Di VPS kita menggunakan *Supervisor*, di cPanel kita akan menggunakan *Cron Job* setiap menit.

1. Buka kembali menu **Cron Jobs** di cPanel.
2. Atur jadwalnya menjadi setiap menit (`* * * * *`).
3. Masukkan perintah berikut (sesuaikan lokasi path PHP dan folder `formatr_core` Anda):
```bash
/usr/local/bin/php /home/username_cpanel/formatr_core/artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

## 9. Konfigurasi Cron Email Otomatis (Jam 08:00 Pagi)

Agar pengingat kalender acara terkirim ke email, buat satu Cron Job terakhir.
1. Atur jadwalnya menjadi setiap menit (`* * * * *`).
2. Masukkan perintah berikut:
```bash
/usr/local/bin/php /home/username_cpanel/formatr_core/artisan schedule:run >> /dev/null 2>&1
```



Berikut adalah langkah-langkah sistematis dan aman untuk memperbarui (update) aplikasi Laravel kamu di cPanel via Git:
Langkah 1: Push Kode dari Komputer/Sistem Lokal ke GitHub
Karena cPanel kamu mengambil (pull) dari repositori Git, pastikan kode yang baru kita ubah ini sudah ter-commit dan di-push ke GitHub milikmu.
- Buka terminal di komputer atau VS Code tempat kode ini berada.
- Jalankan:
git add .
git commit -m "Update: Profil admin mandiri, auto-crop foto sorotan, link daftar, fitur UI"
git push origin main
Langkah 2: Masuk ke Terminal cPanel
Setelah kode berada di GitHub (atau repository Git kamu), kita akan menariknya (pull) ke server cPanel.
1. Login ke akun cPanel kamu.
2. Cari dan klik menu Terminal (biasanya di bawah kategori "Advanced").
3. Di dalam terminal, pindah ke folder core aplikasi kamu:
cd /home/formatrunesa/formatr_core/web_format_r_unesa
(Catatan: pastikan formatrunesa sesuai dengan username cPanel kamu).
Langkah 3: Tarik (Pull) Pembaruan Kode
Jalankan perintah Git untuk mengunduh kode terbaru dari repositori:
git pull origin main
(Pastikan tidak ada pesan konflik. Jika sukses, akan muncul daftar file yang diperbarui seperti ImageUploadTrait.php, web.php, dll).
Langkah 4: Jalankan Migrasi Database
Karena kita menambahkan 2 kolom baru di database (output pada tabel events dan photo_sorotan pada tabel members), kamu wajib menjalankan perintah migrate. Gunakan path PHP 8.3 yang benar:
/opt/cpanel/ea-php83/root/usr/bin/php artisan migrate
(Pastikan muncul tulisan Migrating: ...add_output_to_events_table).
Langkah 5: Bersihkan Cache (Sangat Penting)
Ini wajib dilakukan agar Laravel menyadari adanya rute baru dan kolom baru:
/opt/cpanel/ea-php83/root/usr/bin/php artisan optimize:clear
Langkah 6: Restart Pekerja Latar Belakang (Queue Worker)
Karena kita mengubah logika AI di ProcessMemberPhotoBackground.php dan ImageUploadTrait.php, kita harus mematikan pekerja latar belakang yang lama agar cron job cPanel menghidupkan pekerja yang baru (yang memuat kode terbaru).
/opt/cpanel/ea-php83/root/usr/bin/php artisan queue:restart
---
**Selesai!** Website FORMAT-R Anda kini siap digunakan secara publik di cPanel dengan dukungan AI Remove.bg Multi-API Key!