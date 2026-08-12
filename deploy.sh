#!/bin/bash
# ==============================================================
# deploy.sh — Script deploy aman untuk project FORMAT-R UNESA
# Server: cPanel (formatrunesa@server)
# ==============================================================
# Cara pakai:
#   cd /home/formatrunesa/formatr_core/web_format_r_unesa
#   chmod +x deploy.sh
#   ./deploy.sh
# ==============================================================

set -e  # Hentikan script kalau ada perintah yang gagal

# --- Konfigurasi path, sesuaikan kalau lokasi berubah ---
PROJECT_DIR="/home/formatrunesa/formatr_core/web_format_r_unesa"
PUBLIC_HTML="/home/formatrunesa/public_html"
PUBLIC_DIR="$PROJECT_DIR/public"
STORAGE_LINK="$PROJECT_DIR/public/storage"

echo "=============================================="
echo " DEPLOY FORMAT-R UNESA — $(date '+%Y-%m-%d %H:%M:%S')"
echo "=============================================="

cd "$PROJECT_DIR" || { echo "❌ Gagal masuk ke $PROJECT_DIR"; exit 1; }

# --- 1. Pastikan working tree bersih sebelum pull ---
echo ""
echo "[1/6] Mengecek status git..."
if [ -n "$(git status --porcelain)" ]; then
    echo "❌ ADA PERUBAHAN LOKAL YANG BELUM DI-COMMIT."
    echo "   Deploy dihentikan supaya tidak menimpa/merusak file."
    echo "   Cek dengan: git status"
    echo "   Kalau perubahan itu tidak penting, jalankan: git stash"
    exit 1
fi
echo "✅ Working tree bersih."

# --- 2. Pull HANYA kalau fast-forward, jangan paksa merge ---
echo ""
echo "[2/6] Menarik update dari repository (fast-forward only)..."
if ! git pull --ff-only; then
    echo "❌ GIT PULL GAGAL (kemungkinan ada divergent branch / konflik)."
    echo "   Deploy dihentikan. Periksa manual dengan: git log --oneline --graph -5"
    exit 1
fi
echo "✅ Pull berhasil."

# --- 3. Install dependency & regenerate autoloader ---
echo ""
echo "[3/6] Update dependency composer..."
COMPOSER_BIN="$HOME/composer.phar"
if [ ! -f "$COMPOSER_BIN" ]; then
    echo "❌ composer.phar tidak ditemukan di $COMPOSER_BIN"
    echo "   Install dulu dengan: curl -sS https://getcomposer.org/installer | php"
    exit 1
fi
php "$COMPOSER_BIN" install --no-dev --optimize-autoloader --no-interaction

# Regenerate autoloader dari awal — mencegah error "incomplete object"
# saat unserialize cache lama yang classmap-nya sudah berubah
echo "Regenerate autoloader..."
php "$COMPOSER_BIN" dump-autoload -o

# --- 4. Verifikasi folder public masih berupa direktori yang valid ---
echo ""
echo "[4/6] Verifikasi folder public..."
if [ ! -d "$PUBLIC_DIR" ]; then
    echo "❌ PERINGATAN: $PUBLIC_DIR TIDAK DITEMUKAN sebagai direktori!"
    echo "   Deploy dihentikan sebelum symlink ikut rusak. Cek manual: file $PUBLIC_DIR"
    exit 1
fi
if [ ! -f "$PUBLIC_DIR/index.php" ]; then
    echo "❌ PERINGATAN: index.php tidak ditemukan di public/. Kemungkinan folder rusak/kosong."
    exit 1
fi
echo "✅ Folder public valid."

# --- 5. Verifikasi symlink public_html masih mengarah dengan benar ---
echo ""
echo "[5/6] Verifikasi symlink public_html..."
if [ -L "$PUBLIC_HTML" ]; then
    TARGET=$(readlink -f "$PUBLIC_HTML")
    EXPECTED=$(readlink -f "$PUBLIC_DIR")
    if [ "$TARGET" != "$EXPECTED" ]; then
        echo "⚠️  Symlink public_html mengarah ke tempat yang salah, memperbaiki..."
        rm "$PUBLIC_HTML"
        ln -s "$PUBLIC_DIR" "$PUBLIC_HTML"
        echo "✅ Symlink public_html diperbaiki -> $PUBLIC_DIR"
    else
        echo "✅ Symlink public_html sudah benar."
    fi
elif [ -e "$PUBLIC_HTML" ]; then
    echo "❌ PERINGATAN: public_html ada tapi BUKAN symlink (kemungkinan file/folder biasa)."
    echo "   Ini perlu dicek manual dulu sebelum ditimpa otomatis. Deploy dihentikan."
    exit 1
else
    echo "⚠️  Symlink public_html tidak ditemukan, membuat baru..."
    ln -s "$PUBLIC_DIR" "$PUBLIC_HTML"
    echo "✅ Symlink public_html dibuat -> $PUBLIC_DIR"
fi

# --- 6. Verifikasi symlink storage (untuk akses file upload/foto) ---
echo ""
echo "[6/6] Verifikasi symlink storage..."
if [ ! -L "$STORAGE_LINK" ]; then
    echo "⚠️  Symlink public/storage tidak ditemukan, membuat ulang dengan artisan..."
    php artisan storage:link
else
    echo "✅ Symlink storage sudah ada."
fi

# --- Bersihkan SEMUA cache dulu (application, config, route, view, compiled) ---
echo ""
echo "Membersihkan seluruh cache lama..."
php artisan optimize:clear

# --- Bangun ulang cache config/route/view untuk performa ---
echo "Membangun ulang cache Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "=============================================="
echo " ✅ DEPLOY SELESAI — $(date '+%Y-%m-%d %H:%M:%S')"
echo "=============================================="
