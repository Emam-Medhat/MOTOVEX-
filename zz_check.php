<?php
// Throwaway content checks on the rendered showroom HTML.
foreach (['en', 'ar'] as $locale) {
    $h = file_get_contents(__DIR__ . '/zz_moto_' . $locale . '.html');

    echo strtoupper($locale) . "\n";
    echo '  product cards   : ' . substr_count($h, '<article class="product"') . "\n";
    echo '  order buttons   : ' . substr_count($h, 'order-trigger') . "\n";
    echo '  pager links     : ' . substr_count($h, 'class="pbtn') . "\n";
    echo '  brand tiles     : ' . substr_count($h, 'class="brand-tile"') . "\n";
    echo '  style tiles     : ' . substr_count($h, 'class="category ') . "\n";
    echo '  empty src=""    : ' . preg_match_all('/src=""/', $h) . "\n";
    echo '  php notices     : ' . (preg_match('/(Warning|Notice|Undefined|Exception)/i', strip_tags($h)) ? 'FOUND' : 'none') . "\n";

    preg_match_all('/<span class="price num-latin">(.*?)<\/span>/', $h, $prices);
    echo '  prices          : ' . implode(' | ', array_slice($prices[1], 0, 3)) . "\n";

    preg_match_all('/class="b b-(ok|warn|bad|neutral|feat)">([^<]+)/', $h, $badges);
    echo '  badges          : ' . implode(' | ', array_slice(array_map('trim', $badges[2]), 0, 4)) . "\n";

    preg_match('/tel:([0-9+]+)/', $h, $tel);
    echo '  tel href        : ' . ($tel[0] ?? 'n/a') . "\n";

    preg_match('/<div class="copyright">(.*?)<\/div>/s', $h, $cr);
    echo '  copyright       : ' . trim(preg_replace('/\s+/', ' ', strip_tags($cr[1] ?? 'n/a'))) . "\n";

    preg_match('/<form class="toolbar"/', $h, $t);
    echo '  toolbar form    : ' . ($t ? 'yes' : 'no') . "\n";
}
