<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\Promotion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * 20 realistic promotions covering all brands in the catalogue.
 * Uses updateOrCreate on slug — safe to re-run.
 */
class MotovexPromotionSeeder extends Seeder
{
    public function run(): void
    {
        // ── resolve brands ──────────────────────────────────────────────
        $brands = Brand::whereIn('slug', [
            'yamaha','honda','kawasaki','suzuki','bmw-motorrad',
            'ducati','aprilia','ktm','triumph','mv-agusta',
            'neo','atlas','vayu','aurum','cfmoto',
        ])->get()->keyBy('slug');

        // ── resolve a sample motorcycle for discount linkage ─────────────
        $bikes = Motorcycle::whereIn('slug', [
            'vayu-r1-1100','atlas-traverse-1200','aurum-grandura',
            'neo-s1','vayu-street-790',
        ])->get()->keyBy('slug');

        $promos = [
            // ── 1. Yamaha ─────────────────────────────────────────────────
            [
                'brand_id'       => $brands['yamaha']?->id,
                'motorcycle_id'  => null,
                'title'          => 'Yamaha Summer Sport Sale — 10% Off YZF Series',
                'slug'           => 'yamaha-summer-sport-sale',
                'description'    => 'Score 10% off the full YZF-R supersport range this summer. Includes R3, R7, and R1M. While stocks last.',
                'discount_type'  => 'percentage',
                'discount_value' => 10.00,
                'image_url'      => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(3),
                'end_date'       => now()->addDays(25),
                'is_active'      => true,
            ],
            // ── 2. Honda ──────────────────────────────────────────────────
            [
                'brand_id'       => $brands['honda']?->id,
                'motorcycle_id'  => null,
                'title'          => 'Honda CBR Zero Down-Payment Finance',
                'slug'           => 'honda-cbr-zero-down',
                'description'    => 'Ride home your new Honda CBR with zero down payment and 0% interest for the first 6 months. CBR500R, CBR650R, and CBR1000RR-R included.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now(),
                'end_date'       => now()->addDays(30),
                'is_active'      => true,
            ],
            // ── 3. Kawasaki ───────────────────────────────────────────────
            [
                'brand_id'       => $brands['kawasaki']?->id,
                'motorcycle_id'  => null,
                'title'          => 'Kawasaki Ninja Ownership Package',
                'slug'           => 'kawasaki-ninja-ownership-package',
                'description'    => 'Every new Kawasaki Ninja purchased this month includes a 2-year extended warranty, free first service, and a branded helmet. Limited units.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(2),
                'end_date'       => now()->addDays(18),
                'is_active'      => true,
            ],
            // ── 4. Suzuki ─────────────────────────────────────────────────
            [
                'brand_id'       => $brands['suzuki']?->id,
                'motorcycle_id'  => null,
                'title'          => 'Suzuki GSX-R Trade-In Bonus — EGP 15,000 Off',
                'slug'           => 'suzuki-gsxr-trade-in-bonus',
                'description'    => 'Trade in any motorcycle and receive EGP 15,000 off a new Suzuki GSX-R 750 or GSX-R 1000R. Valid at all MOTOVEX showrooms.',
                'discount_type'  => 'fixed',
                'discount_value' => 15000.00,
                'image_url'      => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now(),
                'end_date'       => now()->addDays(21),
                'is_active'      => true,
            ],
            // ── 5. BMW Motorrad ───────────────────────────────────────────
            [
                'brand_id'       => $brands['bmw-motorrad']?->id,
                'motorcycle_id'  => null,
                'title'          => 'BMW S 1000 RR — Track Day Package',
                'slug'           => 'bmw-s1000rr-track-day',
                'description'    => 'Purchase an S 1000 RR or M 1000 RR and receive a complimentary two-day track experience at a partnered circuit. Includes pro coaching.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(5),
                'end_date'       => now()->addDays(45),
                'is_active'      => true,
            ],
            // ── 6. Ducati ─────────────────────────────────────────────────
            [
                'brand_id'       => $brands['ducati']?->id,
                'motorcycle_id'  => null,
                'title'          => 'Ducati Panigale — 8% Finance Rate',
                'slug'           => 'ducati-panigale-finance',
                'description'    => 'The Panigale V4 is now available with an exclusive 8% annual finance rate and free delivery to your door. Offer valid until end of month.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now(),
                'end_date'       => now()->addDays(28),
                'is_active'      => true,
            ],
            // ── 7. Aprilia ────────────────────────────────────────────────
            [
                'brand_id'       => $brands['aprilia']?->id,
                'motorcycle_id'  => null,
                'title'          => 'Aprilia RS 660 — Free Accessories Bundle',
                'slug'           => 'aprilia-rs660-accessories',
                'description'    => 'Buy an Aprilia RS 660 or Tuono 660 and choose EGP 8,000 worth of factory accessories at no cost. Sport screens, tank bags, tail bags available.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(1),
                'end_date'       => now()->addDays(35),
                'is_active'      => true,
            ],
            // ── 8. KTM ────────────────────────────────────────────────────
            [
                'brand_id'       => $brands['ktm']?->id,
                'motorcycle_id'  => null,
                'title'          => 'KTM Duke Season Opener — 12% Discount',
                'slug'           => 'ktm-duke-season-opener',
                'description'    => 'Season opener deal — get 12% off any KTM Duke model from 390 to 890. Ride season is here and stock is moving fast.',
                'discount_type'  => 'percentage',
                'discount_value' => 12.00,
                'image_url'      => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now(),
                'end_date'       => now()->addDays(14),
                'is_active'      => true,
            ],
            // ── 9. Triumph ────────────────────────────────────────────────
            [
                'brand_id'       => $brands['triumph']?->id,
                'motorcycle_id'  => null,
                'title'          => 'Triumph Speed Triple Heritage Edition Offer',
                'slug'           => 'triumph-speed-triple-heritage',
                'description'    => 'The iconic Speed Triple 1200 RS Heritage Edition is available with exclusive leather seat upgrade and chrome bar-end mirrors at no extra cost.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(7),
                'end_date'       => now()->addDays(60),
                'is_active'      => true,
            ],
            // ── 10. MV Agusta ─────────────────────────────────────────────
            [
                'brand_id'       => $brands['mv-agusta']?->id,
                'motorcycle_id'  => null,
                'title'          => 'MV Agusta F3 800 — Exclusive VIP Handover',
                'slug'           => 'mv-agusta-f3-vip-handover',
                'description'    => 'Every F3 800 or F4 purchase includes a VIP factory handover experience, personalised leather jacket, and priority track-day booking.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now(),
                'end_date'       => now()->addDays(90),
                'is_active'      => true,
            ],
            // ── 11. VAYU (fictional) ──────────────────────────────────────
            [
                'brand_id'       => $brands['vayu']?->id,
                'motorcycle_id'  => $bikes['vayu-r1-1100']?->id,
                'title'          => 'VAYU R1 1100 — Apex Launch Offer 15% Off',
                'slug'           => 'vayu-r1-1100-apex-launch',
                'description'    => 'Celebrate the launch of the VAYU R1 1100 with 15% off the list price. First 30 buyers also receive a complimentary carbon fibre tank pad set.',
                'discount_type'  => 'percentage',
                'discount_value' => 15.00,
                'image_url'      => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(4),
                'end_date'       => now()->addDays(20),
                'is_active'      => true,
            ],
            // ── 12. ATLAS (fictional) ─────────────────────────────────────
            [
                'brand_id'       => $brands['atlas']?->id,
                'motorcycle_id'  => $bikes['atlas-traverse-1200']?->id,
                'title'          => 'ATLAS Traverse 1200 — Adventure Ready Bundle',
                'slug'           => 'atlas-traverse-1200-bundle',
                'description'    => 'Every ATLAS Traverse 1200 comes with a full luggage system (top box + side cases), crash bars, and GPS mount at no additional cost. Limited stock.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(6),
                'end_date'       => now()->addDays(30),
                'is_active'      => true,
            ],
            // ── 13. AURUM (fictional) ─────────────────────────────────────
            [
                'brand_id'       => $brands['aurum']?->id,
                'motorcycle_id'  => $bikes['aurum-grandura']?->id,
                'title'          => 'AURUM Grandura — Heritage Trade-In EGP 20,000',
                'slug'           => 'aurum-grandura-trade-in',
                'description'    => 'Trade in your current machine toward an AURUM Grandura and receive EGP 20,000 trade-in bonus. White-glove handover service included.',
                'discount_type'  => 'fixed',
                'discount_value' => 20000.00,
                'image_url'      => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(5),
                'end_date'       => now()->addMonths(2),
                'is_active'      => true,
            ],
            // ── 14. NÉO (fictional) ───────────────────────────────────────
            [
                'brand_id'       => $brands['neo']?->id,
                'motorcycle_id'  => $bikes['neo-s1']?->id,
                'title'          => 'NÉO S1 — Urban Starter Deal',
                'slug'           => 'neo-s1-urban-starter',
                'description'    => 'First 50 NÉO S1 buyers this quarter receive a free helmet, tank bag, and 1-year roadside assistance subscription.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now(),
                'end_date'       => now()->addDays(40),
                'is_active'      => true,
            ],
            // ── 15. CFMOTO ────────────────────────────────────────────────
            [
                'brand_id'       => $brands['cfmoto']?->id,
                'motorcycle_id'  => null,
                'title'          => 'CFMOTO 450SR — Student & First-Rider Offer',
                'slug'           => 'cfmoto-450sr-student-offer',
                'description'    => 'Students and A2-licence first-time buyers get 8% off any CFMOTO sport model. Proof of student status or licence date required at point of purchase.',
                'discount_type'  => 'percentage',
                'discount_value' => 8.00,
                'image_url'      => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now(),
                'end_date'       => now()->addDays(50),
                'is_active'      => true,
            ],
            // ── 16. End-of-Season All Brands ──────────────────────────────
            [
                'brand_id'       => null,
                'motorcycle_id'  => null,
                'title'          => 'End-of-Season Clearance — Up to 18% Off',
                'slug'           => 'end-of-season-clearance',
                'description'    => 'Selected 2025 model-year bikes across all brands are reduced by up to 18% to make room for new deliveries. First-come, first-served.',
                'discount_type'  => 'percentage',
                'discount_value' => 18.00,
                'image_url'      => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(2),
                'end_date'       => now()->addDays(15),
                'is_active'      => true,
            ],
            // ── 17. Servicing Flash Sale ──────────────────────────────────
            [
                'brand_id'       => null,
                'motorcycle_id'  => null,
                'title'          => 'Flash Service Week — 20% Off All Labour',
                'slug'           => 'flash-service-week',
                'description'    => 'Book any MOTOVEX service appointment this week and receive 20% off all labour charges. Applies to all brands, all models, all service centres.',
                'discount_type'  => 'percentage',
                'discount_value' => 20.00,
                'image_url'      => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now(),
                'end_date'       => now()->addDays(7),
                'is_active'      => true,
            ],
            // ── 18. Referral Bonus ────────────────────────────────────────
            [
                'brand_id'       => null,
                'motorcycle_id'  => null,
                'title'          => 'Refer a Friend — EGP 3,000 Voucher Each',
                'slug'           => 'refer-a-friend-voucher',
                'description'    => 'Refer a friend who completes a purchase and both of you receive an EGP 3,000 service or accessories voucher redeemable at any MOTOVEX location.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(10),
                'end_date'       => now()->addMonths(3),
                'is_active'      => true,
            ],
            // ── 19. Insurance Partnership ─────────────────────────────────
            [
                'brand_id'       => null,
                'motorcycle_id'  => null,
                'title'          => 'Free First Year Insurance on New Bikes',
                'slug'           => 'free-first-year-insurance',
                'description'    => 'MOTOVEX has partnered with a leading insurer to offer complimentary comprehensive insurance for the first 12 months on any new motorcycle purchase over EGP 50,000.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(8),
                'end_date'       => now()->addDays(60),
                'is_active'      => true,
            ],
            // ── 20. Ramadan Special ───────────────────────────────────────
            [
                'brand_id'       => null,
                'motorcycle_id'  => null,
                'title'          => 'Ramadan Season Offer — Zero Registration Fees',
                'slug'           => 'ramadan-zero-registration',
                'description'    => 'During the holy month, MOTOVEX covers all registration and licensing fees on any new motorcycle purchase. Save up to EGP 5,000 on admin costs.',
                'discount_type'  => null,
                'discount_value' => null,
                'image_url'      => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=1200&q=85',
                'start_date'     => now()->subDays(1),
                'end_date'       => now()->addDays(28),
                'is_active'      => true,
            ],
        ];

        $count = 0;
        foreach ($promos as $promo) {
            Promotion::updateOrCreate(
                ['slug' => $promo['slug']],
                $promo
            );
            $count++;
        }

        $this->command->info("✅  {$count} promotions seeded.");
    }
}
