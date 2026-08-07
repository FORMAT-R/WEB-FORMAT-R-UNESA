#!/bin/bash

# Path ke executable PHP di cPanel Anda
PHP_BIN="/opt/cpanel/ea-php83/root/usr/bin/php"

# Path ke root direktori Laravel Anda (Sesuaikan dengan path di production)
PROJECT_DIR="/home/formatrunesa/formatr_core/web_format_r_unesa"

# Cek apakah proses queue:work sedang berjalan
if ! ps aux | grep -v grep | grep "queue:work" > /dev/null
then
    # Jika TIDAK berjalan, catat dan jalankan di background (nohup)
    echo "[$(date)] Queue worker mati. Memulai ulang..." >> /home/formatrunesa/queue_daemon_monitor.log
    
    cd $PROJECT_DIR
    nohup $PHP_BIN artisan queue:work --sleep=3 --tries=3 --timeout=1800 > /home/formatrunesa/queue_worker.log 2>&1 &
    
    echo "[$(date)] Queue worker berhasil dijalankan di PID $!" >> /home/formatrunesa/queue_daemon_monitor.log
fi
