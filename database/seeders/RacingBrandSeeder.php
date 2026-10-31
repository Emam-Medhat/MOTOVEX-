<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

/**
 * Creates the 10 real-world sport/racing motorcycle brands.
 * Uses updateOrCreate to be safely re-runnable.
 */
class RacingBrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name'         => 'Yamaha',
                'slug'         => 'yamaha',
                'segment'      => 'Performance',
                'tagline'      => 'Revs Your Heart',
                'accent_color' => '#003087',
                'description'  => 'Yamaha Motor Company — Japanese manufacturer of world-class sport and supersport motorcycles. The YZF-R series is renowned globally for its MotoGP-derived technology.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/47/Yamaha_Motor_logo.svg/320px-Yamaha_Motor_logo.svg.png',
                'website_url'  => 'https://www.yamahamotorsports.com',
                'country'      => 'Japan',
                'sort_order'   => 10,
                'is_published' => true,
            ],
            [
                'name'         => 'Honda',
                'slug'         => 'honda',
                'segment'      => 'Performance',
                'tagline'      => 'The Power of Dreams',
                'accent_color' => '#CC0000',
                'description'  => "Honda Motor Co. — world's largest motorcycle manufacturer. The CBR series spans entry-level sport to the legendary Fireblade superbike.",
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Honda.svg/320px-Honda.svg.png',
                'website_url'  => 'https://powersports.honda.com',
                'country'      => 'Japan',
                'sort_order'   => 11,
                'is_published' => true,
            ],
            [
                'name'         => 'Kawasaki',
                'slug'         => 'kawasaki',
                'segment'      => 'Performance',
                'tagline'      => 'Let the Good Times Roll',
                'accent_color' => '#3D9900',
                'description'  => 'Kawasaki Heavy Industries — maker of the legendary Ninja supersport range including the ZX-10R Superbike World Championship contender.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Kawasaki_motorcycles_logo.svg/320px-Kawasaki_motorcycles_logo.svg.png',
                'website_url'  => 'https://www.kawasaki.com',
                'country'      => 'Japan',
                'sort_order'   => 12,
                'is_published' => true,
            ],
            [
                'name'         => 'Suzuki',
                'slug'         => 'suzuki',
                'segment'      => 'Performance',
                'tagline'      => 'Way of Life!',
                'accent_color' => '#003087',
                'description'  => 'Suzuki Motor Corporation — creator of the iconic GSX-R line that defined the sport bike segment for four decades.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Suzuki_logo_2.svg/320px-Suzuki_logo_2.svg.png',
                'website_url'  => 'https://suzukicycles.com',
                'country'      => 'Japan',
                'sort_order'   => 13,
                'is_published' => true,
            ],
            [
                'name'         => 'BMW Motorrad',
                'slug'         => 'bmw-motorrad',
                'segment'      => 'Performance',
                'tagline'      => 'Make Life a Ride',
                'accent_color' => '#1C69D4',
                'description'  => 'BMW Motorrad — German precision engineering at its finest. The S 1000 RR and M 1000 RR represent the pinnacle of production superbike performance.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/BMW.svg/240px-BMW.svg.png',
                'website_url'  => 'https://www.bmwmotorrad.com',
                'country'      => 'Germany',
                'sort_order'   => 14,
                'is_published' => true,
            ],
            [
                'name'         => 'Ducati',
                'slug'         => 'ducati',
                'segment'      => 'Performance',
                'tagline'      => 'Desiderio Senza Fine',
                'accent_color' => '#CC0000',
                'description'  => 'Ducati Motor Holding — Italian racing icon. The Panigale V4 dominates Superbike World Championship while the Streetfighter redefines the naked superbike.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/en/thumb/4/4b/Ducati_red_logo.PNG/320px-Ducati_red_logo.PNG',
                'website_url'  => 'https://www.ducati.com',
                'country'      => 'Italy',
                'sort_order'   => 15,
                'is_published' => true,
            ],
            [
                'name'         => 'Aprilia',
                'slug'         => 'aprilia',
                'segment'      => 'Performance',
                'tagline'      => 'Born to Win',
                'accent_color' => '#000000',
                'description'  => "Aprilia — Italian brand with more World Championship titles than any other European manufacturer. The RSV4 is considered one of the best-handling superbikes ever built.",
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Aprilia-logo.svg/320px-Aprilia-logo.svg.png',
                'website_url'  => 'https://www.aprilia.com',
                'country'      => 'Italy',
                'sort_order'   => 16,
                'is_published' => true,
            ],
            [
                'name'         => 'KTM',
                'slug'         => 'ktm',
                'segment'      => 'Performance',
                'tagline'      => 'Ready to Race',
                'accent_color' => '#FF6600',
                'description'  => 'KTM — Austrian manufacturer whose "Ready to Race" ethos is embedded in every motorcycle from the RC 390 entry-level sportbike to the 990 RC R factory racer.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/11/KTM_logo.svg/320px-KTM_logo.svg.png',
                'website_url'  => 'https://www.ktm.com',
                'country'      => 'Austria',
                'sort_order'   => 17,
                'is_published' => true,
            ],
            [
                'name'         => 'Triumph',
                'slug'         => 'triumph',
                'segment'      => 'Performance',
                'tagline'      => 'For the Ride',
                'accent_color' => '#D4AF37',
                'description'  => 'Triumph Motorcycles — British heritage brand reborn. The Daytona 660 brings modern triple-cylinder technology to the supersport class.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Triumph_Motorcycles_logo.svg/320px-Triumph_Motorcycles_logo.svg.png',
                'website_url'  => 'https://www.triumphmotorcycles.com',
                'country'      => 'United Kingdom',
                'sort_order'   => 18,
                'is_published' => true,
            ],
            [
                'name'         => 'MV Agusta',
                'slug'         => 'mv-agusta',
                'segment'      => 'Premium',
                'tagline'      => 'Art & Engineering',
                'accent_color' => '#C8102E',
                'description'  => 'MV Agusta — Italian manufacturer of the most beautiful motorcycles ever made. The F3 is a jewel of supersport engineering and Italian craftsmanship.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/MV_Agusta_logo.svg/320px-MV_Agusta_logo.svg.png',
                'website_url'  => 'https://www.mvagusta.com',
                'country'      => 'Italy',
                'sort_order'   => 19,
                'is_published' => true,
            ],
            [
                'name'         => 'CFMOTO',
                'slug'         => 'cfmoto',
                'segment'      => 'Performance',
                'tagline'      => 'Passion for Riding',
                'accent_color' => '#005BAA',
                'description'  => 'CFMOTO — Chinese manufacturer rapidly gaining global recognition with KTM-derived technology in the affordable sport segment. The 450SR and 675SR-R offer tremendous value.',
                'logo_url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/CFMOTO_logo.svg/320px-CFMOTO_logo.svg.png',
                'website_url'  => 'https://www.cfmoto.com',
                'country'      => 'China',
                'sort_order'   => 20,
                'is_published' => true,
            ],
            [
                'name'         => 'QJMOTOR',
                'slug'         => 'qjmotor',
                'segment'      => 'Performance',
                'tagline'      => 'Speed & Innovation',
                'accent_color' => '#1A1A2E',
                'description'  => 'QJMOTOR (Qianjiang Motor) — Benelli-affiliated Chinese brand producing high-performance sport motorcycles. The SRK 800 RR is a parallel-twin class competitor.',
                'logo_url'     => null,
                'website_url'  => 'https://www.qjmotor.com',
                'country'      => 'China',
                'sort_order'   => 21,
                'is_published' => true,
            ],
        ];

        foreach ($brands as $data) {
            Brand::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('Racing brands seeded: ' . count($brands));
    }
}
