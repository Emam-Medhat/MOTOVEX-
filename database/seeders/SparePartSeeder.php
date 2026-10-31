<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\SparePart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        // Use admin user as creator (created_by)
        $admin = User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))->first()
                 ?? User::first();

        // Grab brand IDs by slug (fallback null = universal part)
        $brands = Brand::whereIn('slug', ['neo', 'atlas', 'vayu', 'aurum'])
                       ->pluck('id', 'slug');

        $parts = [
            // ── ENGINE ──────────────────────────────────────────────────────
            [
                'brand'         => 'vayu',
                'name'          => 'High-Performance Piston Kit 400cc',
                'sku'           => 'ENG-PST-001',
                'category'      => 'engine',
                'description'   => 'Forged aluminum piston kit for 400cc single-cylinder engines. Includes rings, pin, and circlips. Ideal for performance builds.',
                'price'         => 149.99,
                'stock'         => 25,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'Timing Chain Kit Universal',
                'sku'           => 'ENG-TCK-002',
                'category'      => 'engine',
                'description'   => 'OEM-quality timing chain kit compatible with most 150–250cc four-stroke engines. Reduces engine noise and improves timing accuracy.',
                'price'         => 34.99,
                'stock'         => 40,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => 'atlas',
                'name'          => 'Engine Gasket Set — Full Kit',
                'sku'           => 'ENG-GSK-003',
                'category'      => 'engine',
                'description'   => 'Complete engine gasket set including head gasket, base gasket, valve cover and all seals. For 650cc parallel-twin adventure engines.',
                'price'         => 58.00,
                'stock'         => 18,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=700&q=80',
            ],

            // ── BRAKES ──────────────────────────────────────────────────────
            [
                'brand'         => 'vayu',
                'name'          => 'Sintered Brake Pads — Front (Sport)',
                'sku'           => 'BRK-PAD-001',
                'category'      => 'brakes',
                'description'   => 'High-friction sintered metal brake pads for sport riding. Excellent heat resistance, consistent bite from cold and fade-free under hard use.',
                'price'         => 29.99,
                'stock'         => 60,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'Floating Brake Disc 320mm',
                'sku'           => 'BRK-DSC-002',
                'category'      => 'brakes',
                'description'   => 'Stainless steel floating brake rotor 320mm diameter. Wavy design for improved cooling and wet-weather performance.',
                'price'         => 89.00,
                'stock'         => 12,
                'status'        => 'low_stock',
                'image'         => 'https://images.unsplash.com/photo-1558981359-219d6364c9c8?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => 'neo',
                'name'          => 'Braided Brake Line Kit — Front & Rear',
                'sku'           => 'BRK-LNK-003',
                'category'      => 'brakes',
                'description'   => 'Stainless braided brake lines reduce flex for a firmer lever feel. Includes all fittings and banjo bolts.',
                'price'         => 45.00,
                'stock'         => 22,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?auto=format&fit=crop&w=700&q=80',
            ],

            // ── FILTERS & FLUIDS ─────────────────────────────────────────────
            [
                'brand'         => null,
                'name'          => 'High-Flow Air Filter — Universal 43mm',
                'sku'           => 'FLT-AIR-001',
                'category'      => 'filters',
                'description'   => 'Foam high-flow air filter for carbureted and fuel-injected 125–250cc engines. Washable and reusable. Improves throttle response.',
                'price'         => 18.50,
                'stock'         => 80,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1596891350419-e78d47c1706e?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'Motorcycle Engine Oil 10W-40 — 4L',
                'sku'           => 'FLT-OIL-002',
                'category'      => 'filters',
                'description'   => 'Full synthetic 10W-40 motorcycle engine oil. JASO MA2 certified. Suitable for wet-clutch engines. 4-litre bottle.',
                'price'         => 24.99,
                'stock'         => 120,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'Oil Filter — Spin-On (Universal)',
                'sku'           => 'FLT-OFT-003',
                'category'      => 'filters',
                'description'   => 'High-efficiency spin-on oil filter with anti-drain valve. Compatible with most Honda, Yamaha, Kawasaki and Suzuki engines.',
                'price'         => 8.99,
                'stock'         => 150,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=700&q=80',
            ],

            // ── ELECTRICAL ───────────────────────────────────────────────────
            [
                'brand'         => null,
                'name'          => 'Iridium Spark Plug CR8EIX',
                'sku'           => 'ELC-SPK-001',
                'category'      => 'electrical',
                'description'   => 'NGK iridium IX spark plug for improved ignition, fuel efficiency and longevity. Replaces standard CR8E in most 125–400cc engines.',
                'price'         => 12.99,
                'stock'         => 200,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1558981285-6f0c8b8c3e58?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => 'neo',
                'name'          => 'Lithium-Ion Motorcycle Battery 12V 6Ah',
                'sku'           => 'ELC-BAT-002',
                'category'      => 'electrical',
                'description'   => 'Lightweight lithium-ion starter battery. 12V / 6Ah. Replaces lead-acid batteries up to 12Ah. Weight: 0.6 kg vs 2.3 kg lead-acid.',
                'price'         => 79.00,
                'stock'         => 30,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'LED Headlight Conversion Kit H4',
                'sku'           => 'ELC-LED-003',
                'category'      => 'electrical',
                'description'   => 'Plug-and-play H4 LED headlight kit. 6000K white light, 40W draw replaces 60/55W halogen. Includes fan-cooled heat sink.',
                'price'         => 39.99,
                'stock'         => 45,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?auto=format&fit=crop&w=700&q=80',
            ],

            // ── SUSPENSION ───────────────────────────────────────────────────
            [
                'brand'         => 'atlas',
                'name'          => 'Rear Shock Absorber — Adjustable',
                'sku'           => 'SUS-RSA-001',
                'category'      => 'suspension',
                'description'   => 'Adjustable preload rear mono-shock for adventure and trail motorcycles. 310mm eye-to-eye length. Spring rate: 90 N/mm.',
                'price'         => 119.00,
                'stock'         => 14,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'Fork Seal Kit 41mm',
                'sku'           => 'SUS-FSK-002',
                'category'      => 'suspension',
                'description'   => 'Front fork seal and dust seal kit for 41mm inner tubes. Includes 2 oil seals + 2 dust seals. Stops fork oil leaks effectively.',
                'price'         => 22.00,
                'stock'         => 55,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=700&q=80',
            ],

            // ── TIRES & WHEELS ────────────────────────────────────────────────
            [
                'brand'         => null,
                'name'          => 'Rear Tyre 150/70-17 Radial',
                'sku'           => 'TYR-RR-001',
                'category'      => 'tires_wheels',
                'description'   => 'Sport-touring radial rear tyre 150/70-17. Dual-compound tread for straight-line stability and cornering grip. Speed rated W.',
                'price'         => 145.00,
                'stock'         => 20,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1558981359-219d6364c9c8?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'Front Tyre 120/70-17 Radial',
                'sku'           => 'TYR-FR-002',
                'category'      => 'tires_wheels',
                'description'   => 'Sport radial front tyre 120/70-17. Silica compound for wet and dry performance. Speed rated W (270 km/h).',
                'price'         => 125.00,
                'stock'         => 18,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1596891350419-e78d47c1706e?auto=format&fit=crop&w=700&q=80',
            ],

            // ── TRANSMISSION ─────────────────────────────────────────────────
            [
                'brand'         => null,
                'name'          => 'Drive Chain 520 O-Ring 110 Links',
                'sku'           => 'TRN-CHN-001',
                'category'      => 'transmission',
                'description'   => 'Heavy-duty 520 O-ring chain, 110 links. Gold color. Tensile strength 3,100 kgf. Includes master link. Suits 125–400cc bikes.',
                'price'         => 38.99,
                'stock'         => 70,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'Chain & Sprocket Kit 15T/45T 520',
                'sku'           => 'TRN-SPK-002',
                'category'      => 'transmission',
                'description'   => 'Complete drive kit: steel front sprocket 15T, rear sprocket 45T aluminum, 520 O-ring chain 110 links. Fits most naked 250–400cc.',
                'price'         => 74.00,
                'stock'         => 4,
                'status'        => 'low_stock',
                'image'         => 'https://images.unsplash.com/photo-1558981285-6f0c8b8c3e58?auto=format&fit=crop&w=700&q=80',
            ],

            // ── EXHAUST ───────────────────────────────────────────────────────
            [
                'brand'         => 'vayu',
                'name'          => 'Slip-On Exhaust Can — Carbon Fibre 45mm',
                'sku'           => 'EXH-SLP-001',
                'category'      => 'exhaust',
                'description'   => 'Carbon fibre slip-on silencer, 45mm inlet. Saves 1.2 kg over stock. Deeper tone, improved midrange flow. ECE R77 approved.',
                'price'         => 189.00,
                'stock'         => 8,
                'status'        => 'low_stock',
                'image'         => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'brand'         => null,
                'name'          => 'Exhaust Gasket Set (Universal 4-stroke)',
                'sku'           => 'EXH-GSK-002',
                'category'      => 'exhaust',
                'description'   => 'Copper/graphite exhaust port gaskets set. Includes 2 header gaskets and mid-pipe gasket. Seals exhaust leaks and reduces noise.',
                'price'         => 12.50,
                'stock'         => 90,
                'status'        => 'in_stock',
                'image'         => 'https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?auto=format&fit=crop&w=700&q=80',
            ],
        ];

        foreach ($parts as $data) {
            $brandId = $data['brand'] ? ($brands[$data['brand']] ?? null) : null;

            SparePart::create([
                'created_by'          => $admin->id,
                'brand_id'            => $brandId,
                'name'                => $data['name'],
                'slug'                => Str::slug($data['name']) . '-' . strtolower(substr(md5($data['sku']), 0, 5)),
                'sku'                 => $data['sku'],
                'category'            => $data['category'],
                'description'         => $data['description'],
                'compatibility_notes' => null,
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
