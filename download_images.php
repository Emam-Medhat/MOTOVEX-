<?php
/**
 * Downloads real motorcycle images from Wikimedia Commons API.
 * Uses PHP curl directly for reliable SSL handling on Windows.
 * Wikimedia Commons hosts freely licensed media (CC BY, CC BY-SA, public domain).
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

function httpGet($url) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_FOLLOWLOCATION  => true,
        CURLOPT_MAXREDIRS       => 5,
        CURLOPT_TIMEOUT         => 60,
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_SSL_VERIFYHOST  => false,
        CURLOPT_USERAGENT       => 'MOTOVEX-ImageDownloader/1.0 (motorcycle marketplace)',
    ]);
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    return ['data' => $data, 'code' => $code, 'error' => $error];
}

// Motorcycles to download images for: [search_term => desired_filename]
$motorcycles = [
    'Yamaha YZF-R1 motorcycle'   => 'yamaha-yzf-r1.jpg',
    'Yamaha YZF-R7'              => 'yamaha-yzf-r7.jpg',
    'Yamaha YZF-R6'              => 'yamaha-yzf-r6.jpg',
    'Yamaha YZF-R3'              => 'yamaha-yzf-r3.jpg',
    'Kawasaki Ninja ZX-10R'      => 'kawasaki-ninja-zx-10r.jpg',
    'Kawasaki Ninja ZX-6R'       => 'kawasaki-ninja-zx-6r.jpg',
    'Kawasaki Ninja ZX-4RR'      => 'kawasaki-ninja-zx-4rr.jpg',
    'Kawasaki Ninja 400'         => 'kawasaki-ninja-400.jpg',
    'Honda CBR1000RR Fireblade'  => 'honda-cbr1000rr-fireblade.jpg',
    'Honda CBR600RR'             => 'honda-cbr600rr.jpg',
    'Honda CBR650R'              => 'honda-cbr650r.jpg',
    'Honda CBR500R'              => 'honda-cbr500r.jpg',
    'Suzuki GSX-R1000'           => 'suzuki-gsx-r1000r.jpg',
    'Suzuki GSX-8R'              => 'suzuki-gsx-8r.jpg',
    'Suzuki GSX-R750'            => 'suzuki-gsx-r750.jpg',
    'Suzuki GSX-R600'            => 'suzuki-gsx-r600.jpg',
    'BMW S 1000 RR motorcycle'   => 'bmw-s-1000-rr.jpg',
    'BMW M 1000 RR'              => 'bmw-m-1000-rr.jpg',
    'Ducati Panigale V4'         => 'ducati-panigale-v4.jpg',
    'Ducati Panigale V2'         => 'ducati-panigale-v2.jpg',
    'Ducati Streetfighter V4'    => 'ducati-streetfighter-v4.jpg',
    'Aprilia RSV4 motorcycle'    => 'aprilia-rsv4.jpg',
    'Aprilia RS 660'             => 'aprilia-rs-660.jpg',
    'KTM RC 390'                 => 'ktm-rc-390.jpg',
    'KTM 990 RC R'               => 'ktm-990-rc-r.jpg',
    'Triumph Daytona 660'        => 'triumph-daytona-660.jpg',
    'MV Agusta F3 motorcycle'    => 'mv-agusta-f3.jpg',
    'CFMOTO 450SR'               => 'cfmoto-450sr.jpg',
    'CFMOTO 675SR'               => 'cfmoto-675sr-r.jpg',
    'QJMOTOR SRK 800'             => 'qjmotor-srk-800-rr.jpg',
];

$manifest = [];
$disk = Storage::disk('public');
if (!$disk->exists('motorcycles')) {
    $disk->makeDirectory('motorcycles');
}

foreach ($motorcycles as $searchTerm => $filename) {
    $filepath = 'motorcycles/' . $filename;
    $localPath = storage_path('app/public/' . $filepath);

    // Skip if already downloaded and valid
    if ($disk->exists($filepath) && $disk->size($filepath) > 2000) {
        echo "SKIP (exists): $filename\n";
        $manifest[$filename] = ['status' => 'exists', 'source' => 'previously downloaded'];
        continue;
    }

    echo "Searching: $searchTerm ... ";

    try {
        // Step 1: Search for files on Wikimedia Commons
        $searchUrl = 'https://commons.wikimedia.org/w/api.php?' . http_build_query([
            'action'      => 'query',
            'format'      => 'json',
            'list'        => 'search',
            'srsearch'    => $searchTerm,
            'srnamespace' => 6,
            'srlimit'     => 8,
        ]);

        $searchResp = httpGet($searchUrl);
        if ($searchResp['code'] !== 200 || $searchResp['error']) {
            echo "FAIL (search: " . ($searchResp['error'] ?: 'HTTP ' . $searchResp['code']) . ")\n";
            $manifest[$filename] = ['status' => 'failed', 'reason' => $searchResp['error'] ?: 'HTTP ' . $searchResp['code']];
            continue;
        }

        $searchData = json_decode($searchResp['data'], true);
        $results = $searchData['query']['search'] ?? [];

        if (empty($results)) {
            echo "FAIL (no results)\n";
            $manifest[$filename] = ['status' => 'failed', 'reason' => 'no search results'];
            continue;
        }

        // Try each search result to find a usable image
        $downloaded = false;
        foreach ($results as $result) {
            $fileTitle = $result['title'];

            // Skip non-image files
            if (!preg_match('/\.(jpg|jpeg|png)$/i', $fileTitle)) {
                continue;
            }

            // Step 2: Get image info (URL, license)
            $infoUrl = 'https://commons.wikimedia.org/w/api.php?' . http_build_query([
                'action' => 'query',
                'format' => 'json',
                'titles' => $fileTitle,
                'prop'   => 'imageinfo',
                'iiprop' => 'url|extmetadata|size',
            ]);

            $infoResp = httpGet($infoUrl);
            if ($infoResp['code'] !== 200) continue;

            $infoData = json_decode($infoResp['data'], true);
            $pages = $infoData['query']['pages'] ?? [];

            foreach ($pages as $page) {
                if (isset($page['missing'])) continue;
                $imageInfo = $page['imageinfo'][0] ?? null;
                if (!$imageInfo) continue;

                $imageUrl = $imageInfo['url'] ?? null;
                if (!$imageUrl) continue;

                // Skip very small images
                $width  = $imageInfo['width'] ?? 0;
                $height = $imageInfo['height'] ?? 0;
                if ($width < 400 || $height < 300) continue;

                // Step 3: Download the image
                $imgResp = httpGet($imageUrl);
                if ($imgResp['code'] !== 200 || strlen($imgResp['data']) < 5000) continue;

                $body = $imgResp['data'];

                // Verify it's a real image (check magic bytes)
                $magic = substr($body, 0, 4);
                $isValid = (substr($body, 0, 3) === "\xFF\xD8\xFF") // JPEG
                        || (substr($body, 0, 8) === "\x89PNG\r\n\x1a\n"); // PNG

                if (!$isValid) continue;

                // Save to local storage
                file_put_contents($localPath, $body);

                // Get license info
                $extMeta = $imageInfo['extmetadata'] ?? [];
                $license = $extMeta['LicenseShortName']['value'] ?? 'Unknown';
                $artist  = strip_tags($extMeta['Artist']['value'] ?? 'Unknown');

                echo "OK ($width x $height, $license)\n";

                $manifest[$filename] = [
                    'status'     => 'downloaded',
                    'source'     => $imageUrl,
                    'commons'    => 'https://commons.wikimedia.org/wiki/' . urlencode(str_replace(' ', '_', $fileTitle)),
                    'license'    => $license,
                    'artist'     => mb_substr($artist, 0, 200),
                    'dimensions' => $width . 'x' . $height,
                ];
                $downloaded = true;
                break 2;
            }
        }

        if (!$downloaded) {
            echo "FAIL (no suitable image)\n";
            $manifest[$filename] = ['status' => 'failed', 'reason' => 'no suitable image in results'];
        }

    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        $manifest[$filename] = ['status' => 'error', 'reason' => $e->getMessage()];
    }

    usleep(300000); // 0.3s delay
}

// Save manifest
$manifestPath = storage_path('app/public/motorcycles/image-manifest.json');
file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

echo "\n=== SUMMARY ===\n";
$success = count(array_filter($manifest, fn($m) => in_array($m['status'], ['downloaded', 'exists'])));
$failed  = count(array_filter($manifest, fn($m) => in_array($m['status'], ['failed', 'error'])));
echo "Downloaded/Existing: $success\n";
echo "Failed: $failed\n";
echo "Manifest: $manifestPath\n";

if ($failed > 0) {
    echo "\nFailed images:\n";
    foreach ($manifest as $file => $info) {
        if (in_array($info['status'], ['failed', 'error'])) {
            echo "  - $file: " . ($info['reason'] ?? 'unknown') . "\n";
        }
    }
}
