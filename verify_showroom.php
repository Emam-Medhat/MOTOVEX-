<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Motorcycle;
use App\Models\Brand;

echo "=== SHOWROOM VERIFICATION ===\n\n";

// Simulate the index controller query
$total = Motorcycle::with('brand')
    ->where('is_published', true)
    ->whereHas('brand', fn($q) => $q->where('is_published', true))
    ->count();

echo "Published motorcycles visible in showroom: $total\n";

// Count by brand
echo "\nBy brand:\n";
$brands = Brand::where('is_published', true)
    ->withCount(['motorcycles' => fn($q) => $q->where('is_published', true)])
    ->orderBy('name')
    ->get();

foreach ($brands as $b) {
    if ($b->motorcycles_count > 0) {
        echo "  {$b->name}: {$b->motorcycles_count}\n";
    }
}

// Categories
$cats = Motorcycle::published()->distinct()->pluck('category');
echo "\nCategories: " . $cats->implode(', ') . "\n";

// Featured count
$featured = Motorcycle::published()->where('is_featured', true)->count();
echo "Featured: $featured\n";

// Price range
$min = Motorcycle::published()->min('price');
$max = Motorcycle::published()->max('price');
echo "Price range: \${$min} - \${$max}\n";

// Engine range
$minCC = Motorcycle::published()->min('engine_capacity');
$maxCC = Motorcycle::published()->max('engine_capacity');
echo "Engine range: {$minCC}cc - {$maxCC}cc\n";

// Slugs for first page (9 per page)
echo "\nFirst 9 slugs (page 1):\n";
Motorcycle::with('brand')
    ->published()
    ->orderBy('model_name')
    ->limit(9)
    ->get()
    ->each(fn($m) => print("  /motorcycles/{$m->slug}\n"));

echo "\nAll 30 racing bikes have working detail URLs:\n";
$racingSlugs = Motorcycle::whereHas('brand', function($q) {
    $q->whereIn('slug', ['yamaha','honda','kawasaki','suzuki','bmw-motorrad',
        'ducati','aprilia','ktm','triumph','mv-agusta','cfmoto','qjmotor']);
})->pluck('slug');

$ok = 0;
foreach ($racingSlugs as $slug) {
    $moto = Motorcycle::where('slug', $slug)
        ->where('is_published', true)
        ->whereHas('brand', fn($q) => $q->where('is_published', true))
        ->first();
    if ($moto) {
        $ok++;
    } else {
        echo "  MISSING: $slug\n";
    }
}
echo "  $ok / 30 accessible\n";
echo "\nVerification complete.\n";
