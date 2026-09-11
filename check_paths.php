<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kol = \App\Models\KolProfile::first();
echo "KOL photo_path: " . ($kol->photo_path ?? 'null') . "\n";

$brand = \App\Models\Brand::first();
echo "Brand logo_path: " . ($brand->logo_path ?? 'null') . "\n";
