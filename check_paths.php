<?php

use App\Models\Brand;
use App\Models\KolProfile;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$kol = KolProfile::first();
echo 'KOL photo_path: '.($kol->photo_path ?? 'null')."\n";

$brand = Brand::first();
echo 'Brand logo_path: '.($brand->logo_path ?? 'null')."\n";
