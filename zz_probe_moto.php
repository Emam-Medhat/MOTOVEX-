<?php
// Throwaway verification: render the motorcycle showroom through its real controller.
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$controller = app(App\Http\Controllers\MotorcycleController::class);

foreach (['ar', 'en'] as $locale) {
    app()->setLocale($locale);

    $request = Illuminate\Http\Request::create('/motorcycles', 'GET', ['sort' => 'price_desc']);
    $request->setUserResolver(fn () => null);

    $result = $controller->index($request);
    $html   = $result instanceof Illuminate\Contracts\View\View ? $result->render() : (string) $result;

    $leaks = [];
    preg_match_all("/motorcycles\.[a-z0-9_.]+/i", $html, $matches);
    foreach (array_unique($matches[0]) as $candidate) {
        $value = __($candidate);
        if ($value === $candidate) {
            $leaks[] = $candidate;
        }
    }
    // Blade-escaped leftovers like "motorcycles.lp_x" that __() would also leave as-is.
    preg_match_all("/\bmotorcycles\.lp_[a-z0-9_]+/", $html, $raw);
    $rawLeaks = array_unique($raw[0]);

    file_put_contents(__DIR__ . '/zz_moto_' . $locale . '.html', $html);

    echo strtoupper($locale) . ": bytes=" . strlen($html)
        . " | html-tag=" . (preg_match('/<html lang="([a-z]+)" dir="([a-z]+)">/', $html, $m) ? $m[1] . '/' . $m[2] : 'n/a')
        . " | unresolved-lp-keys=" . (count($rawLeaks) ? implode(', ', $rawLeaks) : 'none')
        . "\n";

    foreach (['/motorcycles/', '/cart/add/', 'num-latin', 'order-trigger', 'brand-tile', 'category"'] as $needle) {
        echo '   ' . $needle . ' => ' . (str_contains($html, $needle) ? 'found' : 'MISSING') . "\n";
    }
}
