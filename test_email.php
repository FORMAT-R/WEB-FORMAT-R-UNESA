<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\Mail::raw('Test email dari Laravel via script.', function($message) {
        $message->to('shahrulalfarizky1@gmail.com')->subject('Test Konfigurasi Email');
    });
    echo "Email berhasil dikirim!\n";
} catch (\Exception $e) {
    echo "Gagal: " . $e->getMessage() . "\n";
}
