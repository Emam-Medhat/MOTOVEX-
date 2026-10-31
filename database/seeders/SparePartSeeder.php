<?php

namespace Database\Seeders;

use App\Models\SparePart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Curated spare-parts catalogue.
 *
 * 20 real-world motorcycle parts (NGK, Brembo, EBC, DID, JT, K&N, Hiflofiltro,
 * Motul, Yuasa, Öhlins, SKF, Akrapovič, Michelin, Wiseco, Cometic…) with real
 * motorcycle compatibility notes.
 *
 * Images are real product photographs sourced from Wikimedia Commons and stored
 * locally under storage/app/public/spare-parts/ (served through the public disk).
 */
class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        // Use admin user as creator (created_by)
        $admin = User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))->first()
                 ?? User::first();

        // Replace the demo catalogue with the curated real-world set.
        SparePart::query()->delete();

        $parts = [
            // ── ELECTRICAL ────────────────────────────────────────────────────
            [
                'name'          => 'NGK Iridium IX Spark Plug CR9EIX',
                'sku'           => 'ELC-SPK-CR9EIX',
                'category'      => 'electrical',
                'description'   => 'NGK Iridium IX spark plug with a 0.6 mm iridium centre electrode for a stronger, more consistent spark, easier cold starts and improved fuel economy. Sold as a single plug.',
                'compatibility' => 'Honda CBR650R / CB650R, Yamaha MT-07 / XSR700, Kawasaki Z650, Suzuki SV650 — most 400–900cc four-stroke engines using a 10 mm thread.',
                'price'         => 450.00,
                'stock'         => 180,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/ngk-iridium-spark-plug-cr9eix.jpg',
            ],
            [
                'name'          => 'Yuasa YTZ10S AGM Maintenance-Free Battery 12V',
                'sku'           => 'ELC-BAT-YTZ10S',
                'category'      => 'electrical',
                'description'   => 'Yuasa YTZ10S sealed AGM battery, factory-activated and maintenance-free. 12V 8.6Ah (10HR). Delivers strong cranking power and a longer service life than conventional lead-acid batteries.',
                'compatibility' => 'Kawasaki Ninja 400 / Z400, Honda CBR500R / CB500X, Yamaha YZF-R3, BMW G310R.',
                'price'         => 2950.00,
                'stock'         => 26,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/yuasa-agm-battery-ytz10s.jpg',
            ],
            [
                'name'          => 'LED Headlight Conversion Bulb H4 6000K',
                'sku'           => 'ELC-LED-H4',
                'category'      => 'electrical',
                'description'   => 'Plug-and-play H4 LED headlight bulb, 6000K cool white, 40W draw replacing a 60/55W halogen. Fan-cooled heat sink and CANbus-ready driver for flicker-free operation.',
                'compatibility' => 'Universal H4 (P43t) socket — fits most naked, sport and touring motorcycles with a standard H4 headlight.',
                'price'         => 950.00,
                'stock'         => 60,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/led-headlight-bulb-h4.jpg',
            ],

            // ── BRAKES ────────────────────────────────────────────────────────
            [
                'name'          => 'Brembo Sintered Front Brake Pads 07BB19.07',
                'sku'           => 'BRK-PAD-BR07BB19',
                'category'      => 'brakes',
                'description'   => 'Brembo genuine sintered front brake pads. High friction coefficient with excellent heat resistance and a consistent, fade-free bite under hard braking. Sold as a set for one caliper.',
                'compatibility' => 'Yamaha MT-07 / XSR700 / Ténéré 700, Yamaha YZF-R6, Triumph Street Triple 675.',
                'price'         => 1850.00,
                'stock'         => 40,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/brembo-sintered-front-brake-pads.jpg',
            ],
            [
                'name'          => 'Brembo 4-Piston Radial Brake Caliper',
                'sku'           => 'BRK-CAL-M4',
                'category'      => 'brakes',
                'description'   => 'Brembo cast monobloc 4-piston radial caliper with 100 mm mounting centres. Delivers huge stopping power, superb rigidity and improved lever feel — ideal for track and fast-road builds.',
                'compatibility' => 'Yamaha MT-09 / MT-10, Kawasaki Z900, Suzuki GSX-R750, most motorcycles with radial 100 mm mounts.',
                'price'         => 9800.00,
                'stock'         => 6,
                'status'        => 'low_stock',
                'image'         => 'spare-parts/brembo-radial-brake-caliper.jpg',
            ],
            [
                'name'          => 'EBC Contour Floating Front Brake Disc 320mm',
                'sku'           => 'BRK-DSC-EBC320',
                'category'      => 'brakes',
                'description'   => 'EBC Contour floating front brake rotor, 320 mm. Stainless steel braking surface with an aluminium alloy hub and wavy profile for better cooling, lighter weight and improved wet-weather bite.',
                'compatibility' => 'Honda CB650R / CBR650R / CB650F, Honda CB500X (upgrade).',
                'price'         => 3200.00,
                'stock'         => 14,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/ebc-floating-front-brake-disc.jpg',
            ],

            // ── TRANSMISSION ──────────────────────────────────────────────────
            [
                'name'          => 'DID 520VX3 X-Ring Drive Chain 120 Links',
                'sku'           => 'TRN-CHN-520VX3',
                'category'      => 'transmission',
                'description'   => 'DID 520VX3 X-ring drive chain with 120 links. Gold-coloured, factory pre-stretched, with an average tensile strength of 3,700 kgf. Includes a rivet master link.',
                'compatibility' => 'Honda CB500X / NC750X, Kawasaki Versys 650, Suzuki V-Strom 650 — most 500–800cc chains running a 520 pitch.',
                'price'         => 2450.00,
                'stock'         => 55,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/did-520vx3-x-ring-drive-chain.jpg',
            ],
            [
                'name'          => 'JT Sprockets Chain & Sprocket Kit 520 (15T/45T)',
                'sku'           => 'TRN-SPK-1545',
                'category'      => 'transmission',
                'description'   => 'Complete drive-train refresh kit: hardened steel 15T front sprocket, 45T rear sprocket and a 520 O-ring chain with 112 links. Restores smooth, quiet power delivery.',
                'compatibility' => 'Kawasaki Ninja 400 / Z400, Kawasaki Ninja 300, Yamaha YZF-R3, most 250–400cc twins.',
                'price'         => 3900.00,
                'stock'         => 22,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/jt-chain-and-sprocket-kit-520.jpg',
            ],

            // ── FILTERS & FLUIDS ─────────────────────────────────────────────
            [
                'name'          => 'K&N High-Flow Air Filter HA-6513',
                'sku'           => 'FLT-AIR-HA6513',
                'category'      => 'filters',
                'description'   => 'K&N washable, reusable high-flow replacement air filter. Cotton-gauze element increases airflow for better throttle response, while still trapping fine dust. Designed to last the life of the engine.',
                'compatibility' => 'Honda CB650R / CBR650R / CB650F, Honda CB500X / CBR500R.',
                'price'         => 1450.00,
                'stock'         => 35,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/kn-high-flow-air-filter.jpg',
            ],
            [
                'name'          => 'Hiflofiltro Premium Oil Filter HF204RC',
                'sku'           => 'FLT-OIL-HF204',
                'category'      => 'filters',
                'description'   => 'Hiflofiltro HF204RC premium spin-on oil filter with an anti-drain-back valve and high-efficiency filtration media. Original-equipment quality — the world\'s first TÜV-approved motorcycle oil filter.',
                'compatibility' => 'Yamaha MT-07 / Ténéré 700 / XSR700 / MT-09, Yamaha YZF-R6, and most Yamaha four-stroke engines.',
                'price'         => 320.00,
                'stock'         => 140,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/hiflofiltro-oil-filter-hf204.jpg',
            ],
            [
                'name'          => 'Motul 7100 10W-40 Fully Synthetic Engine Oil 4L',
                'sku'           => 'FLT-OIL-MOTUL7100',
                'category'      => 'filters',
                'description'   => 'Motul 7100 4T 10W-40 100% synthetic ester-based engine oil, JASO MA2 approved and safe for wet clutches. Delivers outstanding shear resistance, gearbox protection and engine cleanliness. 4-litre bottle.',
                'compatibility' => 'Universal 4-stroke motorcycle engines with a wet clutch requiring 10W-40 (JASO MA2).',
                'price'         => 2100.00,
                'stock'         => 90,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/motul-7100-engine-oil-10w40.jpg',
            ],

            // ── SUSPENSION ────────────────────────────────────────────────────
            [
                'name'          => 'Öhlins STX 36 Rear Shock Absorber',
                'sku'           => 'SUS-RSA-STX36',
                'category'      => 'suspension',
                'description'   => 'Öhlins STX 36 emulsion-type rear mono-shock with adjustable preload, rebound and compression damping. Transformer-grade damping for razor-sharp handling and improved comfort.',
                'compatibility' => 'Yamaha MT-07 / XSR700 / Ténéré 700, Yamaha YZF-R7.',
                'price'         => 12500.00,
                'stock'         => 5,
                'status'        => 'low_stock',
                'image'         => 'spare-parts/ohlins-stx36-rear-shock.jpg',
            ],
            [
                'name'          => 'SKF Fork Seal & Dust Wiper Kit 41mm',
                'sku'           => 'SUS-FSK-41',
                'category'      => 'suspension',
                'description'   => 'SKF dual-compound fork oil seal and dust wiper kit for 41 mm forks. The low-friction seal compound reduces stiction for a plusher, more controlled front end while keeping oil in and dirt out.',
                'compatibility' => 'Honda CB650R / CBR650F, Honda CB500X, Yamaha MT-07, Kawasaki Z650 — motorcycles with 41 mm fork tubes.',
                'price'         => 780.00,
                'stock'         => 48,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/skf-fork-seal-kit-41mm.jpg',
            ],

            // ── EXHAUST ───────────────────────────────────────────────────────
            [
                'name'          => 'Akrapovič Slip-On Line Titanium Exhaust',
                'sku'           => 'EXH-SLP-AKR',
                'category'      => 'exhaust',
                'description'   => 'Akrapovič Slip-On Line silencer in titanium with a carbon-fibre end cap. Saves weight over the stock exhaust and delivers a deeper, sportier tone with a modest mid-range gain. ECE approved.',
                'compatibility' => 'Yamaha MT-07 / XSR700 / Ténéré 700 (2014–2023).',
                'price'         => 18900.00,
                'stock'         => 4,
                'status'        => 'low_stock',
                'image'         => 'spare-parts/akrapovic-slip-on-titanium-exhaust.jpg',
            ],

            // ── TIRES & WHEELS ────────────────────────────────────────────────
            [
                'name'          => 'Michelin Pilot Road 5 Rear Tyre 180/55 ZR17',
                'sku'           => 'TYR-REAR-1805517',
                'category'      => 'tires_wheels',
                'description'   => 'Michelin Pilot Road 5 sport-touring rear tyre, 180/55 ZR17 (73W). XST+ siped tread maintains grip in the wet even as the tyre wears. Ideal for high-mileage all-weather riding.',
                'compatibility' => 'Yamaha MT-09, Kawasaki Z900, Honda CB650R, Triumph Street Triple — most 600cc+ sport-touring fitments.',
                'price'         => 5600.00,
                'stock'         => 18,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/michelin-pilot-road-5-rear-tyre.jpg',
            ],
            [
                'name'          => 'Forged Aluminium Front Wheel Rim 17"',
                'sku'           => 'TYR-RIM-FR17',
                'category'      => 'tires_wheels',
                'description'   => 'Lightweight forged aluminium front wheel rim, 17 inch. Up to 20% lighter than a cast equivalent, reducing unsprung weight for quicker steering and improved suspension response.',
                'compatibility' => 'Universal 17-inch front fitment — most naked and sport motorcycles.',
                'price'         => 8200.00,
                'stock'         => 10,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/forged-aluminium-front-wheel-rim.jpg',
            ],

            // ── ENGINE ────────────────────────────────────────────────────────
            [
                'name'          => 'Wiseco Forged Piston Kit 13.5:1',
                'sku'           => 'ENG-PST-WIS',
                'category'      => 'engine',
                'description'   => 'Wiseco forged high-compression piston kit (13.5:1) with rings, pin and circlips. Forged construction withstands higher cylinder pressures for a reliable power gain in race and performance builds.',
                'compatibility' => 'Honda CRF450R, Honda CRF450RX.',
                'price'         => 6800.00,
                'stock'         => 12,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/wiseco-forged-piston-kit.jpg',
            ],
            [
                'name'          => 'Cometic Complete Engine Gasket Kit',
                'sku'           => 'ENG-GSK-COM',
                'category'      => 'engine',
                'description'   => 'Cometic complete engine gasket and seal kit, including multi-layer steel head gasket, base gasket, valve-cover gasket and all oil seals. Everything needed for a full top-end rebuild.',
                'compatibility' => 'Yamaha YZ450F, Yamaha WR450F.',
                'price'         => 3200.00,
                'stock'         => 16,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/cometic-engine-gasket-kit.jpg',
            ],

            // ── ACCESSORIES ───────────────────────────────────────────────────
            [
                'name'          => 'Bar-End Adjustable Rearview Mirror Pair',
                'sku'           => 'ACC-MIR-BE',
                'category'      => 'accessories',
                'description'   => 'Pair of bar-end rearview mirrors in billet aluminium with a fully adjustable arm. Clean café-racer look with a wide, vibration-resistant rear view. Fits 13–19 mm handlebar ends.',
                'compatibility' => 'Universal — most naked, retro and café-racer motorcycles.',
                'price'         => 1250.00,
                'stock'         => 30,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/bar-end-rearview-mirror-pair.jpg',
            ],
            [
                'name'          => 'CNC Adjustable Brake & Clutch Lever Set',
                'sku'           => 'ACC-LVR-CNC',
                'category'      => 'accessories',
                'description'   => 'CNC-machined folding brake and clutch lever set in anodised aluminium, with 6-position reach adjustment. Folding tips survive a drop, and the adjustable span suits every hand size.',
                'compatibility' => 'Universal fitment — supplied with adapters for the most common master-cylinder and perch types.',
                'price'         => 1650.00,
                'stock'         => 24,
                'status'        => 'in_stock',
                'image'         => 'spare-parts/cnc-adjustable-lever-set.jpg',
            ],
        ];

        foreach ($parts as $data) {
            SparePart::create([
                'created_by'          => $admin->id,
                'brand_id'            => null,
                'name'                => $data['name'],
                'slug'                => Str::slug($data['name']) . '-' . strtolower(substr(md5($data['sku']), 0, 5)),
                'sku'                 => $data['sku'],
                'category'            => $data['category'],
                'description'         => $data['description'],
                'compatibility_notes' => $data['compatibility'],
                'price'               => $data['price'],
                'currency'            => 'EGP',
                'stock_quantity'      => $data['stock'],
                'availability_status' => $data['status'],
                'image_path'          => $data['image'],
                'additional_images'   => null,
                'is_published'        => true,
            ]);
        }
    }
}
