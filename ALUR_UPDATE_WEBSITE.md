# Alur Update Website FORMAT-R UNESA

Panduan singkat setiap kali ada perubahan kode yang mau di-deploy ke server (cPanel).

---

## 1. Di komputer lokal (PC)

```powershell
# Pastikan kerja dari branch main dan sudah update
git pull origin main

# ... lakukan perubahan kode seperti biasa ...

# Cek dulu apa saja yang berubah
git status

# Commit perubahan
git add .
git commit -m "deskripsi singkat perubahan"

# Push ke GitHub
git push origin main
```

**Kalau `git push` ditolak** (`rejected`, `fetch first`, atau `diverged`) — artinya ada perubahan lain di GitHub yang belum kamu tarik (misal dari perubahan langsung di server). Jangan panik:
```powershell
git pull origin main
```
- Kalau tidak ada conflict → lanjut `git push origin main`.
- Kalau ada conflict → editor akan menandai bagian yang bentrok dengan `<<<<<<<`, `=======`, `>>>>>>>`. Putuskan versi mana yang benar, hapus tanda-tanda itu, simpan file, lalu:
  ```powershell
  git add <nama-file-yang-di-edit>
  git commit
  git push origin main
  ```
  (Kalau muncul editor Vim untuk pesan commit: tekan `Esc`, ketik `:wq`, lalu Enter)

---

## 2. Di server (Terminal cPanel)

```bash
cd /home/formatrunesa/formatr_core/web_format_r_unesa
./deploy.sh
```

Script ini otomatis akan:
1. Cek working tree bersih (berhenti kalau ada perubahan lokal belum di-commit di server)
2. `git pull --ff-only` (berhenti kalau ada konflik/divergent, tidak memaksa merge)
3. `composer install` (pakai `~/composer.phar`, sudah dikonfigurasi di script)
4. Verifikasi folder `public/` masih valid
5. Verifikasi & perbaiki symlink `public_html` kalau perlu
6. Verifikasi & buat ulang symlink `storage` kalau perlu
7. Refresh cache Laravel (`config:cache`, `route:cache`, `view:cache`)

---

## 3. Kalau ada migration baru

`deploy.sh` **tidak otomatis** menjalankan migration (sengaja, supaya tidak tidak sengaja mengubah struktur database tanpa sadar). Jalankan manual setelah deploy:

```bash
php artisan migrate:status   # cek dulu migration mana yang "Pending"
php artisan migrate --force  # jalankan kalau memang perlu
```

---

## 4. Kalau setelah update muncul error 500 / halaman aneh

```bash
# Cek pesan error sebenarnya
grep -n "production.ERROR" storage/logs/laravel.log | tail -3
sed -n '<nomor_baris_yang_muncul>,+5p' storage/logs/laravel.log
```

Penyebab paling umum yang sudah pernah kejadian:
- **Cache basi** setelah migration/struktur data berubah → `php artisan cache:clear`
- **Migration belum dijalankan** → cek `php artisan migrate:status`
- **Symlink `public_html` atau `storage` rusak** → `deploy.sh` biasanya otomatis perbaiki, tapi kalau masih masalah, cek manual: `ls -la /home/formatrunesa/public_html` dan `ls -la public/storage`

---

## Aturan penting supaya tidak rusak lagi

1. **Jangan edit file langsung di server** kecuali darurat. Kalau terpaksa, langsung `git add`, `git commit`, `git push` dari server juga saat itu juga — supaya lokal dan server tidak diverge lama.
2. **Selalu `git pull` di lokal sebelum mulai kerja baru**, supaya tidak ketinggalan perubahan dari tempat lain.
3. **Jangan pernah `git pull` biasa di server production** — pakai `deploy.sh` yang sudah pakai `--ff-only`, supaya kalau ada konflik, prosesnya berhenti dengan jelas alih-alih memaksa merge yang berisiko merusak file (seperti kejadian `public_html` sebelumnya).
4. **Backup folder `storage/app/public`** secara berkala di luar git (foto/upload tidak ikut ter-track git, jadi tidak akan pulih lewat `git checkout` kalau hilang).
