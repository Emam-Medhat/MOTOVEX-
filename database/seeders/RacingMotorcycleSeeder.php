<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Motorcycle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds 30 real-world sport & racing motorcycles.
 *
 * Image strategy:
 *  - Wikimedia Commons downloads  → /storage/motorcycles/<name>.jpg  (asset('storage/...'))
 *  - public/images/ fallbacks      → /images/<name>.jpg
 *
 * Prices are indicative catalog prices sourced from manufacturer
 * MSRP data (USD) as of 2024–2025 model year; not verified offers.
 *
 * Uses updateOrCreate keyed on slug → safe to re-run.
 */
class RacingMotorcycleSeeder extends Seeder
{
    // ── Image URL helpers ──────────────────────────────────────────────────────

    /** Relative path for image in storage/app/public/motorcycles/ */
    private function storageImg(string $filename): string
    {
        $path = storage_path('app/public/motorcycles/' . $filename);
        if (file_exists($path) && filesize($path) > 2000) {
            // Validate image dimensions
            $info = @getimagesize($path);
            if ($info && $info[0] >= 400 && $info[1] >= 300) {
                // Check aspect ratio — skip if it looks like a logo/banner
                $ratio = $info[1] / $info[0];
                if ($ratio >= 0.4 && $ratio <= 2.5) {
                    return '/storage/motorcycles/' . $filename;
                }
            }
        }
        return '';
    }

    /** Relative path for image in public/images/ */
    private function publicImg(string $filename): string
    {
        $path = public_path('images/' . $filename);
        if (file_exists($path)) {
            return '/images/' . $filename;
        }
        return '';
    }

    /** Pick first valid image, return relative path */
    private function img(string $storageFile, string $publicFallback = ''): string
    {
        $url = $this->storageImg($storageFile);
        if ($url) return $url;
        if ($publicFallback) return $this->publicImg($publicFallback);
        return '';
    }

    // ── Unique slug helper ─────────────────────────────────────────────────────
    private function uniqueSlug(string $base): string
    {
        $slug     = Str::slug($base);
        $original = $slug;
        $count    = 1;
        while (Motorcycle::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }
        return $slug;
    }

    // ── Main ───────────────────────────────────────────────────────────────────
    public function run(): void
    {
        $brands = Brand::pluck('id', 'slug');

        // Helper: resolve brand ID, warn if missing
        $b = function (string $slug) use ($brands): int {
            if (!$brands->has($slug)) {
                $this->command->warn("Brand missing: $slug — skipping motorcycle.");
                return 0;
            }
            return $brands[$slug];
        };

        $motorcycles = [

            // ── YAMAHA ────────────────────────────────────────────────────────
            [
                'brand_slug'          => 'yamaha',
                'model_name'          => 'Yamaha YZF-R1',
                'slug'                => 'yamaha-yzf-r1-2024',
                'short_description'   => 'MotoGP crossplane engine in a track-ready superbike. The definitive open-class sportbike.',
                'description'         => 'The Yamaha YZF-R1 is powered by a 998 cc inline-four crossplane crankshaft engine producing around 200 PS. Derived directly from Yamaha\'s MotoGP programme, it features Öhlins electronic suspension, six-axis IMU, traction control, slide control, and launch control. Its aerodynamic bodywork features integrated winglets for downforce. The R1 has won multiple Superbike World Championship titles. Indicative catalog price — not a verified offer.',
                'image_url'           => $this->img('yamaha-yzf-r1.jpg'),
                'engine_capacity'     => 998.00,
                'horsepower'          => 200,
                'torque'              => 113,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 17399.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],
            [
                'brand_slug'          => 'yamaha',
                'model_name'          => 'Yamaha YZF-R7',
                'slug'                => 'yamaha-yzf-r7-2024',
                'short_description'   => 'CP2 twin-cylinder soul wrapped in R-series race-bred bodywork. The accessible supersport.',
                'description'         => 'Introduced in 2021, the YZF-R7 brings the acclaimed 689 cc CP2 parallel-twin engine into a full-fairing supersport chassis. Derived from the MT-07, the R7 features adjustable suspension, lightweight trellis frame, and R1-inspired aerodynamic bodywork. It bridges the gap between entry-level sport bikes and full superbikes, making it ideal for track days and spirited road riding alike. Indicative catalog price.',
                'image_url'           => $this->img('yamaha-yzf-r7.jpg'),
                'engine_capacity'     => 689.00,
                'horsepower'          => 73,
                'torque'              => 67,
                'transmission'        => '6-speed',
                'price'               => 8999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'yamaha',
                'model_name'          => 'Yamaha YZF-R6',
                'slug'                => 'yamaha-yzf-r6-2024',
                'short_description'   => 'The 600 cc supersport legend. High-revving inline-four that defined a generation.',
                'description'         => 'The Yamaha YZF-R6 houses a 599 cc inline-four engine capable of revving to 16,500 rpm. Its ram air system and high-compression pistons produce a thrilling 117 PS. Featuring a deltabox aluminium frame, fully adjustable suspension, and cornering ABS, the R6 has been a dominant force in supersport racing. Currently sold as a Track Edition in most markets. Indicative catalog price.',
                'image_url'           => $this->img('yamaha-yzf-r6.jpg'),
                'engine_capacity'     => 599.00,
                'horsepower'          => 117,
                'torque'              => 61,
                'transmission'        => '6-speed',
                'price'               => 12199.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'yamaha',
                'model_name'          => 'Yamaha YZF-R3',
                'slug'                => 'yamaha-yzf-r3-2024',
                'short_description'   => 'Full-fairing entry-level sportbike. Perfect first step into the R-series family.',
                'description'         => 'The YZF-R3 is powered by a 321 cc parallel-twin engine producing 42 PS. It shares the same R-DNA styling as its bigger siblings, with twin-spar aluminium frame, KYB suspension, and ABS as standard. Ideal for beginners and A2-licence riders, it remains competitive in the R3 Cup race series. Indicative catalog price.',
                'image_url'           => $this->img('yamaha-yzf-r3.jpg'),
                'engine_capacity'     => 321.00,
                'horsepower'          => 42,
                'torque'              => 29,
                'transmission'        => '6-speed',
                'price'               => 5299.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],

            // ── KAWASAKI ──────────────────────────────────────────────────────
            [
                'brand_slug'          => 'kawasaki',
                'model_name'          => 'Kawasaki Ninja ZX-10R',
                'slug'                => 'kawasaki-ninja-zx-10r-2024',
                'short_description'   => 'Multiple Superbike World Champion. The pinnacle of Kawasaki racing DNA.',
                'description'         => 'The Ninja ZX-10R is powered by a 998 cc inline-four producing 203 PS. It features Showa BFRC-lite suspension, Brembo Stylema calipers, Kawasaki IMU, traction control, launch control, and cornering ABS. The ZX-10R has dominated the Superbike World Championship with Jonathan Rea winning six consecutive titles aboard it. Indicative catalog price.',
                'image_url'           => $this->img('kawasaki-ninja-zx-10r.jpg'),
                'engine_capacity'     => 998.00,
                'horsepower'          => 203,
                'torque'              => 114,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 16999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],
            [
                'brand_slug'          => 'kawasaki',
                'model_name'          => 'Kawasaki Ninja ZX-6R',
                'slug'                => 'kawasaki-ninja-zx-6r-2024',
                'short_description'   => '636 cc inline-four supersport with MotoGP-inspired electronics. Built for the track.',
                'description'         => 'The ZX-6R uses a 636 cc inline-four engine displacing beyond the traditional 600 cc class for extra torque without sacrificing the razor-sharp handling that defines supersport riding. Features include dual radial-mount front Brembo brakes, adjustable suspension, traction control, and power mode selector. Indicative catalog price.',
                'image_url'           => $this->img('kawasaki-ninja-zx-6r.jpg'),
                'engine_capacity'     => 636.00,
                'horsepower'          => 130,
                'torque'              => 70,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 11499.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'kawasaki',
                'model_name'          => 'Kawasaki Ninja ZX-4RR',
                'slug'                => 'kawasaki-ninja-zx-4rr-2024',
                'short_description'   => '399 cc inline-four supersport. More cylinders than displacement — pure sport DNA.',
                'description'         => 'The ZX-4RR is a genuine inline-four supersport in the 400 cc class — a configuration that was once reserved for Japan-domestic models. Its 399 cc engine revs freely to 16,000 rpm producing 77 PS. Features include full-adjustable suspension, Brembo-caliper front brakes, quickshifter, and full-colour TFT instrumentation. Indicative catalog price.',
                'image_url'           => $this->img('kawasaki-ninja-zx-4rr.jpg', 'images (3).jpg'),
                'engine_capacity'     => 399.00,
                'horsepower'          => 77,
                'torque'              => 40,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 8999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],
            [
                'brand_slug'          => 'kawasaki',
                'model_name'          => 'Kawasaki Ninja 400',
                'slug'                => 'kawasaki-ninja-400-2024',
                'short_description'   => 'Best-in-class 400 cc parallel-twin. The benchmark entry-level sportbike.',
                'description'         => 'The Ninja 400 set the benchmark for the 400 cc class when launched in 2018. A 399 cc parallel-twin produces 45 PS with a lightweight trellis frame ensuring razor-sharp handling. Dual-channel ABS, adjustable brake lever, and radial-mount front calipers are standard. Ideal for new riders and an extremely capable track machine in the right hands. Indicative catalog price.',
                'image_url'           => $this->img('kawasaki-ninja-400.jpg'),
                'engine_capacity'     => 399.00,
                'horsepower'          => 45,
                'torque'              => 38,
                'transmission'        => '6-speed',
                'price'               => 5299.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],

            // ── HONDA ─────────────────────────────────────────────────────────
            [
                'brand_slug'          => 'honda',
                'model_name'          => 'Honda CBR1000RR-R Fireblade',
                'slug'                => 'honda-cbr1000rr-r-fireblade-2024',
                'short_description'   => 'MotoGP RC213V-S technology in a road-legal superbike. The Fireblade reborn.',
                'description'         => 'The CBR1000RR-R Fireblade SP shares its engine architecture with the RC213V-S MotoGP-derived machine. The 999.9 cc inline-four produces 217 PS with a 14,500 rpm redline. Öhlins NPX pressurised forks and TTX36 rear shock, six-axis IMU, Honda Selectable Torque Control, cornering ABS, and wheelie control make it one of the most electronically sophisticated superbikes available. Indicative catalog price.',
                'image_url'           => $this->img('honda-cbr1000rr-fireblade.jpg'),
                'engine_capacity'     => 999.90,
                'horsepower'          => 217,
                'torque'              => 113,
                'transmission'        => '6-speed + quickshifter/autoblipper',
                'price'               => 28500.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],
            [
                'brand_slug'          => 'honda',
                'model_name'          => 'Honda CBR600RR',
                'slug'                => 'honda-cbr600rr-2024',
                'short_description'   => 'The 600 cc supersport icon with HRC race pedigree. Cornering ABS standard.',
                'description'         => 'The CBR600RR returns with cornering ABS and Honda Electronic Steering Damper. Its 599 cc inline-four uses RAM Air pressure to produce a genuine 120 PS. The aluminium twin-spar frame and Unit Pro-Link rear suspension deliver class-leading handling. Historically dominant in World Supersport racing. Indicative catalog price.',
                'image_url'           => $this->img('honda-cbr600rr.jpg'),
                'engine_capacity'     => 599.00,
                'horsepower'          => 120,
                'torque'              => 65,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 11999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'honda',
                'model_name'          => 'Honda CBR650R',
                'slug'                => 'honda-cbr650r-2024',
                'short_description'   => 'Middleweight sport with an inline-four character. The everyday performance choice.',
                'description'         => 'The CBR650R pairs a 649 cc inline-four engine producing 95 PS with a sporty but practical chassis. Now featuring Honda\'s E-Clutch assist-and-slipper system, full-LED lighting, and IMU-assisted ABS. It bridges the gap between beginner-friendly sport bikes and full supersports — thrilling on track, comfortable on the road. Indicative catalog price.',
                'image_url'           => $this->img('honda-cbr650r.jpg'),
                'engine_capacity'     => 649.00,
                'horsepower'          => 95,
                'torque'              => 63,
                'transmission'        => '6-speed + E-Clutch',
                'price'               => 9499.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'honda',
                'model_name'          => 'Honda CBR500R',
                'slug'                => 'honda-cbr500r-2024',
                'short_description'   => '471 cc parallel-twin sportbike. Full-fairing fun with everyday usability.',
                'description'         => 'The CBR500R\'s 471 cc parallel-twin produces 47 PS — exactly A2-licence compliant in many markets. Its full fairing, riding position, and Honda\'s refined chassis make it one of the best entry-level sport bikes. Anti-lock brakes, smartphone connectivity via Honda RoadSync, and LED lighting are standard. Indicative catalog price.',
                'image_url'           => $this->img('honda-cbr500r.jpg'),
                'engine_capacity'     => 471.00,
                'horsepower'          => 47,
                'torque'              => 43,
                'transmission'        => '6-speed',
                'price'               => 6999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],

            // ── SUZUKI ────────────────────────────────────────────────────────
            [
                'brand_slug'          => 'suzuki',
                'model_name'          => 'Suzuki GSX-R1000R',
                'slug'                => 'suzuki-gsx-r1000r-2024',
                'short_description'   => 'The original Gixxer. Four decades of sport bike perfection refined to its purest form.',
                'description'         => 'The GSX-R1000R uses a 999.8 cc inline-four with Variable Valve Timing — a first for a litre-class superbike. Producing 202 PS, it features Showa BFF forks, Brembo M50 calipers, Suzuki Traction Control System, and Launch Control System. The Motion Track Brake System with ten-axis IMU provides cornering ABS. Indicative catalog price.',
                'image_url'           => $this->img('suzuki-gsx-r1000r.jpg'),
                'engine_capacity'     => 999.80,
                'horsepower'          => 202,
                'torque'              => 117,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 17499.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],
            [
                'brand_slug'          => 'suzuki',
                'model_name'          => 'Suzuki GSX-8R',
                'slug'                => 'suzuki-gsx-8r-2024',
                'short_description'   => 'New-generation 776 cc parallel-twin sportbike. All-day sport performance.',
                'description'         => 'The GSX-8R is Suzuki\'s return to the middleweight sport segment with a brand-new 776 cc parallel-twin engine delivering 82 PS and strong mid-range torque. Its steel twin-spar frame, fully adjustable KYB suspension, traction control, and cornering ABS make it a capable all-rounder sport bike. Indicative catalog price.',
                'image_url'           => $this->img('suzuki-gsx-8r.jpg'),
                'engine_capacity'     => 776.00,
                'horsepower'          => 82,
                'torque'              => 78,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 8999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'suzuki',
                'model_name'          => 'Suzuki GSX-R750',
                'slug'                => 'suzuki-gsx-r750-2024',
                'short_description'   => 'The sweet spot of the GSX-R range. Litre-bike power in a 600-class chassis.',
                'description'         => 'The GSX-R750 occupies the legendary "sweet spot" — a 750 cc inline-four producing 150 PS in a lightweight chassis closer in dimensions to the 600. It uses the same fully adjustable KYB suspension, Brembo radial calipers, and chassis geometry as the 1000, creating a bike that rewards skilled riding more than raw horsepower. Indicative catalog price.',
                'image_url'           => $this->img('suzuki-gsx-r750.jpg', 'images (4).jpg'),
                'engine_capacity'     => 749.00,
                'horsepower'          => 150,
                'torque'              => 85,
                'transmission'        => '6-speed',
                'price'               => 11199.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'suzuki',
                'model_name'          => 'Suzuki GSX-R600',
                'slug'                => 'suzuki-gsx-r600-2024',
                'short_description'   => 'Pure 600 supersport engineering. The track-focused inline-four at its finest.',
                'description'         => 'The GSX-R600 is a 599 cc inline-four producing 125 PS. Ram Air induction, radial-mount Brembo calipers, fully adjustable KYB suspension front and rear, and a revised frame make it one of the sharpest-handling 600s available. Proven in World Supersport and endurance racing globally. Indicative catalog price.',
                'image_url'           => $this->img('suzuki-gsx-r600.jpg'),
                'engine_capacity'     => 599.00,
                'horsepower'          => 125,
                'torque'              => 66,
                'transmission'        => '6-speed',
                'price'               => 9999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],

            // ── BMW MOTORRAD ──────────────────────────────────────────────────
            [
                'brand_slug'          => 'bmw-motorrad',
                'model_name'          => 'BMW S 1000 RR',
                'slug'                => 'bmw-s-1000-rr-2024',
                'short_description'   => 'German precision superbike. ShiftCam engine, active suspension, asymmetric aerodynamics.',
                'description'         => 'The BMW S 1000 RR features a 999 cc inline-four with BMW ShiftCam variable valve timing and lift technology producing 210 PS. BMW Dynamic Damping Control (DDC) electronic suspension, Brembo race compound ABS, and a 6-axis IMU provide an unmatched electronics package. Its asymmetric headlight design has become iconic in the superbike class. Indicative catalog price.',
                'image_url'           => $this->img('bmw-s-1000-rr.jpg'),
                'engine_capacity'     => 999.00,
                'horsepower'          => 210,
                'torque'              => 113,
                'transmission'        => '6-speed + quickshifter/autoblipper',
                'price'               => 16995.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],
            [
                'brand_slug'          => 'bmw-motorrad',
                'model_name'          => 'BMW M 1000 RR',
                'slug'                => 'bmw-m-1000-rr-2024',
                'short_description'   => 'BMW\'s first M motorcycle. Carbon aero wings, titanium rods, race-only electronics.',
                'description'         => 'The M 1000 RR is BMW Motorrad\'s first model to carry the M designation. It produces 212 PS from its heavily revised ShiftCam inline-four with titanium connecting rods. Carbon fibre aerodynamic winglets generate significant downforce, while the full M kit includes Öhlins electronic suspension, carbon fibre wheels, and an M GPS lap trigger. The most extreme production superbike BMW has built. Indicative catalog price.',
                'image_url'           => $this->img('bmw-m-1000-rr.jpg'),
                'engine_capacity'     => 999.00,
                'horsepower'          => 212,
                'torque'              => 113,
                'transmission'        => '6-speed + M Endurance chain',
                'price'               => 34290.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],

            // ── DUCATI ────────────────────────────────────────────────────────
            [
                'brand_slug'          => 'ducati',
                'model_name'          => 'Ducati Panigale V4',
                'slug'                => 'ducati-panigale-v4-2024',
                'short_description'   => 'MotoGP Desmosedici-derived V4 engine. The most powerful Ducati production superbike ever.',
                'description'         => 'The Panigale V4\'s 1,103 cc Desmosedici Stradale V4 engine produces 215.5 PS at 13,000 rpm. Front-frame aerodynamic wings generate downforce reducing front lift by up to 30 kg at 270 km/h. Ducati Vehicle Observer, Cornering ABS Evo, Ducati Traction Control Evo, Slide Control, Wheelie Control Evo, and Engine Brake Control Evo are all standard. Multiple Superbike World Champion. Indicative catalog price.',
                'image_url'           => $this->img('ducati-panigale-v4.jpg', 'images (5).jpg'),
                'engine_capacity'     => 1103.00,
                'horsepower'          => 215,
                'torque'              => 124,
                'transmission'        => '6-speed + Ducati Quick Shift Evo 3',
                'price'               => 21995.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],
            [
                'brand_slug'          => 'ducati',
                'model_name'          => 'Ducati Panigale V2',
                'slug'                => 'ducati-panigale-v2-2024',
                'short_description'   => '955 cc Superquadro twin. Italian superbike soul in a more accessible package.',
                'description'         => 'The Panigale V2 uses the 955 cc Superquadro Mono Desmodromic twin-cylinder engine producing 155 PS. Its aluminium monocoque frame, Öhlins 43 mm NIX-30 fork, and Sachs rear monoshock deliver genuine superbike dynamics. Ducati Quick Shift Evo 2, cornering ABS, traction control, and wheelie control are all included. Race-ready without the V4\'s complexity. Indicative catalog price.',
                'image_url'           => $this->img('ducati-panigale-v2.jpg', 'images (6).jpg'),
                'engine_capacity'     => 955.00,
                'horsepower'          => 155,
                'torque'              => 104,
                'transmission'        => '6-speed + DQS Evo 2',
                'price'               => 14995.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'ducati',
                'model_name'          => 'Ducati Streetfighter V4',
                'slug'                => 'ducati-streetfighter-v4-2024',
                'short_description'   => 'Panigale V4 power without the fairing. The world\'s most powerful naked superbike.',
                'description'         => 'The Streetfighter V4 takes the Panigale V4\'s 1,103 cc Desmosedici Stradale engine and removes the bodywork, creating an aggressive naked superbike producing 208 PS. Integrated front aerodynamic wings, full Ducati electronics package including cornering ABS Evo, traction control, slide control, and the iconic twin-headlight face design make it unmistakably Ducati. Indicative catalog price.',
                'image_url'           => $this->img('ducati-streetfighter-v4.jpg', 'images (7).jpg'),
                'engine_capacity'     => 1103.00,
                'horsepower'          => 208,
                'torque'              => 123,
                'transmission'        => '6-speed + DQS Evo 3',
                'price'               => 21295.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'naked',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],

            // ── APRILIA ───────────────────────────────────────────────────────
            [
                'brand_slug'          => 'aprilia',
                'model_name'          => 'Aprilia RSV4',
                'slug'                => 'aprilia-rsv4-2024',
                'short_description'   => 'V4 superbike with 65° angle. The most awarded superbike of the past decade.',
                'description'         => 'The Aprilia RSV4\'s unique 65° V4 engine produces 217 PS — more than any other standard-production superbike. APRC electronic package includes Cornering ABS, Traction Control, Wheelie Control, and Launch Control derived from the Aprilia Racing WSBK programme. Öhlins NIX-30 fork, TTX36 rear shock, and Brembo Stylema calipers. Multiple WSBK Champion. Indicative catalog price.',
                'image_url'           => $this->img('aprilia-rsv4.jpg', 'images (8).jpg'),
                'engine_capacity'     => 1099.00,
                'horsepower'          => 217,
                'torque'              => 125,
                'transmission'        => '6-speed + quickshifter/autoblipper',
                'price'               => 21999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],
            [
                'brand_slug'          => 'aprilia',
                'model_name'          => 'Aprilia RS 660',
                'slug'                => 'aprilia-rs-660-2024',
                'short_description'   => '660 cc parallel-twin supersport. Aprilia\'s accessible entry into full-fairing performance.',
                'description'         => 'The RS 660 uses a 659 cc parallel-twin engine from the Tuono 660, producing 100 PS in an ultra-lightweight package. At just 169 kg wet, it delivers a power-to-weight ratio that eclipses many 1000 cc bikes. APRC electronics, cornering ABS, traction control, and Öhlins TTX 25 rear shock are standard on the Trophy model. Indicative catalog price.',
                'image_url'           => $this->img('aprilia-rs-660.jpg', 'images (9).jpg'),
                'engine_capacity'     => 659.00,
                'horsepower'          => 100,
                'torque'              => 67,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 10499.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],

            // ── KTM ───────────────────────────────────────────────────────────
            [
                'brand_slug'          => 'ktm',
                'model_name'          => 'KTM RC 390',
                'slug'                => 'ktm-rc-390-2024',
                'short_description'   => '373 cc single-cylinder sportbike. Track-bred DNA in the most accessible KTM.',
                'description'         => 'The KTM RC 390 is powered by a 373 cc single-cylinder liquid-cooled engine producing 43 PS. A WP APEX upside-down fork, Brembo-caliper front brake, cornering ABS, traction control, and superbike-style footpeg position make it a genuine mini-superbike. The RC 390 Cup race series has produced numerous champions who progressed to Moto3 and beyond. Indicative catalog price.',
                'image_url'           => $this->img('ktm-rc-390.jpg', 'images (10).jpg'),
                'engine_capacity'     => 373.00,
                'horsepower'          => 43,
                'torque'              => 37,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 5799.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'ktm',
                'model_name'          => 'KTM 990 RC R',
                'slug'                => 'ktm-990-rc-r-2024',
                'short_description'   => 'KTM\'s new liter-class V-twin sportbike. LC8c engine, track-only electronics suite.',
                'description'         => 'The KTM 990 RC R is powered by the new LC8c 75° V-twin producing 121 PS, tuned for maximum mid-range torque and rideability. WP APEX Pro fully adjustable suspension, Brembo Stylema calipers, and the full electronics suite from MX to Street mode. A motorcycle that bridges KTM\'s race heritage with road-going sport performance. Indicative catalog price.',
                'image_url'           => $this->img('ktm-990-rc-r.jpg', 'images (11).jpg'),
                'engine_capacity'     => 990.00,
                'horsepower'          => 121,
                'torque'              => 103,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 13499.00,
                'currency'            => 'USD',
                'year'                => 2025,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],

            // ── TRIUMPH ───────────────────────────────────────────────────────
            [
                'brand_slug'          => 'triumph',
                'model_name'          => 'Triumph Daytona 660',
                'slug'                => 'triumph-daytona-660-2024',
                'short_description'   => 'British triple reborn. 660 cc three-cylinder supersport with Triumph Street Triple heart.',
                'description'         => 'The Daytona 660 marks Triumph\'s triumphant return to the supersport class after a decade. Its 660 cc inline-three cylinder engine produces 95 PS with a characterful exhaust note unique among sportbikes. Fully adjustable Showa suspension, Brembo four-piston radial front calipers, cornering ABS, and ride-by-wire with multiple riding modes. Indicative catalog price.',
                'image_url'           => $this->img('triumph-daytona-660.jpg', 'images (12).jpg'),
                'engine_capacity'     => 660.00,
                'horsepower'          => 95,
                'torque'              => 69,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 9995.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],

            // ── MV AGUSTA ─────────────────────────────────────────────────────
            [
                'brand_slug'          => 'mv-agusta',
                'model_name'          => 'MV Agusta F3',
                'slug'                => 'mv-agusta-f3-2024',
                'short_description'   => 'Italian art on two wheels. 798 cc three-cylinder with counterrotating crankshaft.',
                'description'         => 'The MV Agusta F3 is considered one of the most beautiful motorcycles ever produced. Its 798 cc inline-triple with counterrotating crankshaft produces 147 PS with an unmistakable sound signature. The MVICS electronics package, Marzocchi fork, Sachs rear shock, and Brembo M4.32 four-piston radial calipers deliver Italian artisanal quality with genuine performance. Indicative catalog price.',
                'image_url'           => $this->img('mv-agusta-f3.jpg'),
                'engine_capacity'     => 798.00,
                'horsepower'          => 147,
                'torque'              => 88,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 16990.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => true,
            ],

            // ── CFMOTO ────────────────────────────────────────────────────────
            [
                'brand_slug'          => 'cfmoto',
                'model_name'          => 'CFMOTO 450SR',
                'slug'                => 'cfmoto-450sr-2024',
                'short_description'   => '449 cc parallel-twin sportbike co-developed with KTM. Class-leading value proposition.',
                'description'         => 'The CFMOTO 450SR is co-developed with KTM and uses the same 449 cc parallel-twin engine found in the KTM 390 Duke family. Producing 47 PS in a fully-faired sportbike package with USD fork, monoshock rear, Brembo brakes, and a full-colour TFT instrument cluster. It delivers genuine sport performance at a fraction of the price of Japanese rivals. Indicative catalog price.',
                'image_url'           => $this->img('cfmoto-450sr.jpg', 'images (13).jpg'),
                'engine_capacity'     => 449.00,
                'horsepower'          => 47,
                'torque'              => 43,
                'transmission'        => '6-speed',
                'price'               => 4999.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
            [
                'brand_slug'          => 'cfmoto',
                'model_name'          => 'CFMOTO 675SR-R',
                'slug'                => 'cfmoto-675sr-r-2024',
                'short_description'   => '675 cc inline-triple supersport. Track-focused Chinese challenger with European ambition.',
                'description'         => 'The CFMOTO 675SR-R is an ambitious supersport powered by a 675 cc inline-triple engine producing approximately 95 PS. Featuring fully adjustable suspension, radial Brembo braking, quickshifter, and cornering ABS, it is CFMOTO\'s most serious track-focused offering targeting the European 600-class supersport segment. Indicative catalog price.',
                'image_url'           => $this->img('cfmoto-675sr-r.jpg', 'images (14).jpg'),
                'engine_capacity'     => 675.00,
                'horsepower'          => 95,
                'torque'              => 65,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 8499.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],

            // ── QJMOTOR ───────────────────────────────────────────────────────
            [
                'brand_slug'          => 'qjmotor',
                'model_name'          => 'QJMOTOR SRK 800 RR',
                'slug'                => 'qjmotor-srk-800-rr-2024',
                'short_description'   => '798 cc parallel-twin sport from Benelli\'s parent company. Punching above its price class.',
                'description'         => 'The QJMOTOR SRK 800 RR is produced by Qianjiang Motor, parent company of Benelli. Its 798 cc parallel-twin engine produces approximately 80 PS with strong mid-range torque. Fully adjustable USD front fork, radial-mount front brakes, cornering ABS, traction control, quickshifter, and a 5-inch colour TFT display make it a compelling full-fairing sport offering in the sub-$8,000 segment. Indicative catalog price.',
                'image_url'           => $this->img('qjmotor-srk-800-rr.jpg', 'images (15).jpg'),
                'engine_capacity'     => 798.00,
                'horsepower'          => 80,
                'torque'              => 72,
                'transmission'        => '6-speed + quickshifter',
                'price'               => 7599.00,
                'currency'            => 'USD',
                'year'                => 2024,
                'category'            => 'sport',
                'availability_status' => 'available',
                'condition'           => 'new',
                'is_published'        => true,
                'is_featured'         => false,
            ],
        ];

        $added   = 0;
        $skipped = 0;

        foreach ($motorcycles as $data) {
            $brandSlug = $data['brand_slug'];
            $brandId   = $b($brandSlug);

            if (!$brandId) {
                $skipped++;
                continue;
            }

            unset($data['brand_slug']);
            $data['brand_id'] = $brandId;

            // Safe upsert — keyed on slug, never overwrites existing records
            // with different slugs (existing demo bikes are untouched)
            $exists = Motorcycle::where('slug', $data['slug'])->exists();
            if ($exists) {
                // Update image_url if currently empty
                Motorcycle::where('slug', $data['slug'])
                    ->whereNull('image_url')
                    ->orWhere('image_url', '')
                    ->update(['image_url' => $data['image_url']]);
                $skipped++;
                continue;
            }

            Motorcycle::create($data);
            $added++;
        }

        $this->command->info("Racing motorcycles: $added added, $skipped skipped (already exist).");
        $this->command->info('Total motorcycles now: ' . Motorcycle::count());
    }
}
