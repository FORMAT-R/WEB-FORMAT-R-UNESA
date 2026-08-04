<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Cabinet;
use App\Models\Member;

$c = Cabinet::create([
    'name' => 'Kolaborasi Asa',
    'period' => '2026/2027',
    'start_year' => 2026,
    'is_active' => true
]);

Member::whereNull('cabinet_id')->update(['cabinet_id' => $c->id]);

echo "Done.\n";
