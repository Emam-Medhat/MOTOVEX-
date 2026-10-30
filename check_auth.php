<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Admin Spare Parts Access Check (after Policy fix) ===\n\n";

$policy = new App\Policies\SparePartPolicy();
$users  = App\Models\User::with('roles')->take(10)->get();

foreach ($users as $u) {
    // Create a dummy SparePart to check non-static methods
    $sp = new App\Models\SparePart(['created_by' => $u->id, 'is_published' => false]);
    $canViewAny = $policy->viewAny($u) ? 'ALLOWED' : 'DENIED';
    $canCreate  = $policy->create($u) ? 'ALLOWED' : 'DENIED';

    echo "ID:{$u->id} | {$u->name} | role:{$u->roleName()} | viewAny:{$canViewAny} | create:{$canCreate}\n";
}

echo "\n=== Route Check ===\n";
$router = app('router');
$routes = collect($router->getRoutes())->map(fn($r) => $r->getName())->filter()->values();
$spRoutes = $routes->filter(fn($n) => str_contains((string)$n, 'spare-parts'))->values();
echo "Spare parts routes (" . $spRoutes->count() . "):\n";
foreach ($spRoutes as $r) echo "  - $r\n";

echo "\n=== OrderItem columns ===\n";
$cols = Schema::getColumnListing('order_items');
echo implode(', ', $cols) . "\n";
