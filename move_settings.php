<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Cabinet;
use App\Models\Setting;

$c = Cabinet::first();
if ($c) {
    $settings = Setting::pluck('value', 'key')->toArray();
    $c->name = $settings['cabinetName'] ?? 'Kolaborasi Asa';
    $c->logo = $settings['cabinetLogo'] ?? null;
    $c->vision = $settings['cabinetVision'] ?? '';
    $c->mission = $settings['cabinetMission'] ?? '';
    $c->save();

    // delete settings
    Setting::whereIn('key', ['cabinetName', 'cabinetLogo', 'cabinetVision', 'cabinetMission'])->delete();
}

echo "Done.\n";
