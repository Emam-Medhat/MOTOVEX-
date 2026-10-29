<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$racingSlugs = ['yamaha','honda','kawasaki','suzuki','bmw-motorrad',
    'ducati','aprilia','ktm','triumph','mv-agusta','cfmoto','qjmotor'];

$bikes = App\Models\Motorcycle::whereHas('brand', function($q) use ($racingSlugs) {
    $q->whereIn('slug', $racingSlugs);
})->orderBy('model_name')->get();

echo "=== IMAGE URL AUDIT ===\n\n";
foreach ($bikes as $m) {
    $img = $m->image_url;
    if (empty($img)) {
        echo "[NO IMAGE]   " . $m->model_name . "\n";
        continue;
    }
    // Determine source type
    if (str_contains($img, '/storage/motorcycles/')) {
        $file = basename(parse_url($img, PHP_URL_PATH));
        $path = storage_path('app/public/motorcycles/' . $file);
        $size = file_exists($path) ? round(filesize($path) / 1024) . ' KB' : 'FILE MISSING';
        echo "[STORAGE OK] " . $m->model_name . " => " . $file . " ($size)\n";
    } elseif (str_contains($img, '/images/')) {
        $file = ltrim(parse_url($img, PHP_URL_PATH), '/');
        $path = public_path($file);
        $exists = file_exists($path) ? 'OK' : 'FILE MISSING';
        echo "[PUBLIC  $exists] " . $m->model_name . " => " . $file . "\n";
    } else {
        echo "[EXTERNAL]   " . $m->model_name . " => " . substr($img, 0, 80) . "\n";
    }
}
