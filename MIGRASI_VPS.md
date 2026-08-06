# Panduan Migrasi Website FORMAT-R UNESA ke VPS (Ubuntu 22.04 / 24.04)

Panduan ini mencakup instalasi LEMP Stack (Linux, Nginx, MySQL, PHP), instalasi Python untuk AI Rembg, konfigurasi Supervisor untuk *Background Jobs* (Remove BG Otomatis), penjadwalan *Cron Job* (Email Otomatis), serta pengaturan SMTP.

## Spesifikasi Minimal VPS yang Disarankan
- OS: Ubuntu 22.04 LTS atau 24.04 LTS
- RAM: Minimal 4GB
- CPU: 2 Core atau lebih
- Storage: 40GB+ NVMe/SSD

---

## 1. Persiapan Awal Server (Initial Server Setup)

Setelah membeli VPS, masuk (login) ke server menggunakan SSH:
```bash
ssh root@IP_ADDRESS_VPS_ANDA
```

Update dan upgrade package bawaan Ubuntu:
```bash
apt update && apt upgrade -y
```

## 2. Instalasi LEMP Stack (Nginx, MySQL, PHP)

### A. Install Nginx (Web Server)
```bash
apt install nginx -y
systemctl start nginx
systemctl enable nginx
```

### B. Install MySQL (Database)
```bash
apt install mysql-server -y
```
Amankan instalasi MySQL:
```bash
mysql_secure_installation
```
*(Jawab `Y` untuk semua pertanyaan keamanan, dan buat password root MySQL).*

Buat Database untuk Website:
```bash
mysql -u root -p
```
Di dalam prompt MySQL, jalankan:
```sql
CREATE DATABASE formatr_db;
CREATE USER 'formatr_user'@'localhost' IDENTIFIED BY 'PasswordKuatAnda123!';
GRANT ALL PRIVILEGES ON formatr_db.* TO 'formatr_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### C. Install PHP 8.3 & Ekstensinya
Tambahkan repositori PHP terbaru (Ondrej):
```bash
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update
```
Install PHP 8.3 beserta ekstensi yang dibutuhkan Laravel dan GD (untuk potong gambar):
```bash
apt install php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd php8.3-intl unzip curl -y
```

## 3. Instalasi Composer & Node.js

### A. Composer (Package Manager PHP)
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### B. Node.js & NPM (Untuk *Build* Aset Vite)
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

## 4. Instalasi Python & Library AI (Rembg)

Website ini bergantung pada Python untuk menghapus latar belakang.
```bash
apt install python3 python3-pip python3-venv -y
```
Install `rembg` dengan dukungan CPU:
```bash
pip3 install "rembg[cpu]" --break-system-packages
```
*(Catatan: Flag `--break-system-packages` seringkali dibutuhkan pada Ubuntu versi terbaru saat menggunakan pip sebagai root).*

## 5. Mengunggah/Kloning Source Code Website

Arahkan terminal ke folder public Nginx:
```bash
cd /var/www/
```
Ada dua cara mengunggah:
1. **Via Git/GitHub:** `git clone https://github.com/akun/format-r-unesa.git formatr`
2. **Via SCP/FileZilla:** Upload folder project Anda dari komputer lokal ke `/var/www/formatr`.

Masuk ke folder website:
```bash
cd /var/www/formatr
```

Berikan hak akses pada folder storage dan bootstrap cache:
```bash
chown -R www-data:www-data /var/www/formatr
chmod -R 775 /var/www/formatr/storage
chmod -R 775 /var/www/formatr/bootstrap/cache
```

## 6. Setup Konfigurasi Laravel (.env)

Salin file konfigurasi awal:
```bash
cp .env.example .env
```
Edit file `.env` menggunakan editor Nano:
```bash
nano .env
```
Sesuaikan bagian ini:
```env
APP_NAME="FORMAT-R UNESA"
APP_ENV=production
APP_KEY= # (Nanti di-generate)
APP_DEBUG=false
APP_URL=https://domainanda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=formatr_db
DB_USERNAME=formatr_user
DB_PASSWORD=PasswordKuatAnda123!

QUEUE_CONNECTION=database
```

Buat kunci aplikasi dan jalankan instalasi:
```bash
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan storage:link
npm install
npm run build
```

## 7. Setup Konfigurasi SMTP (Untuk Fitur Email)

Untuk mengirim email (seperti lupakan password atau notifikasi email setiap jam 08:00 pagi), disarankan menggunakan akun Gmail khusus.

1. Login ke Akun Google Anda.
2. Aktifkan **Verifikasi 2 Langkah (2-Step Verification)** di setelan keamanan akun Google.
3. Buat **Sandi Aplikasi (App Passwords)**:
   - Ke Pengaturan Akun Google -> Keamanan -> Sandi Aplikasi (App Passwords).
   - Buat sandi baru (misal dinamakan "Website FORMAT-R").
   - Google akan memberikan 16 karakter sandi khusus (contoh: `abcd efgh ijkl mnop`).
4. Kembali edit file `.env` di VPS (`nano .env`), dan masukkan detail SMTP:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=emailanda@gmail.com
MAIL_PASSWORD="16_KARAKTER_SANDI_APLIKASI_TANPA_SPASI"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="emailanda@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 8. Konfigurasi Supervisor (Sangat Penting untuk Auto Remove BG)

Karena fitur AI pemotong foto menggunakan `Queue Job`, kita membutuhkan *Supervisor* untuk menjaganya tetap hidup 24/7.

Install Supervisor:
```bash
apt install supervisor -y
```

Buat file konfigurasi worker:
```bash
nano /etc/supervisor/conf.d/formatr-worker.conf
```
Isi dengan konfigurasi berikut:
```ini
[program:formatr-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/formatr/artisan queue:work --timeout=1800 --tries=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/formatr/storage/logs/worker.log
stopwaitsecs=3600
```
Simpan, lalu jalankan/aktifkan supervisor:
```bash
supervisorctl reread
supervisorctl update
supervisorctl start formatr-worker:*
```

## 9. Konfigurasi Cron Job (Untuk Notifikasi Email Jam 08:00 Pagi)

Sistem Laravel memiliki perintah penjadwalan notifikasi. Anda harus memicu Laravel Schedule melalui Cron Job Linux agar sistem bisa mengirim email notifikasi harian tersebut secara otomatis setiap pagi.

Ketik perintah berikut di terminal:
```bash
crontab -e
```

Tambahkan baris ini di baris paling bawah:
```text
* * * * * cd /var/www/formatr && php artisan schedule:run >> /dev/null 2>&1
```

*(Simpan dan keluar. Cron Job akan otomatis berjalan mengecek task Laravel setiap menit).*

## 10. Konfigurasi Nginx (Menyambungkan Domain)

Buat file konfigurasi web:
```bash
nano /etc/nginx/sites-available/formatr
```

Isi dengan:
```nginx
server {
    listen 80;
    server_name domainanda.com www.domainanda.com;
    root /var/www/formatr/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan Nginx Block:
```bash
ln -s /etc/nginx/sites-available/formatr /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

## 11. Install SSL Certificate (HTTPS Gratis via Certbot)

```bash
apt install certbot python3-certbot-nginx -y
certbot --nginx -d domainanda.com -d www.domainanda.com
```

Pilih `Y` untuk *redirect* otomatis ke HTTPS. 

---
**Selesai!** Website FORMAT-R UNESA Anda kini sepenuhnya mengudara dengan fitur AI *Background Removal*, *SMTP Email*, dan sistem *Auto Notification* yang berjalan di belakang layar secara otomatis.
