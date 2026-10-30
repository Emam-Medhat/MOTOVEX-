<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\Enquiry;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\User;
use App\Policies\BrandPolicy;
use App\Policies\MotorcyclePolicy;
use App\Policies\EnquiryPolicy;
use App\Policies\ServicePolicy;
use App\Policies\SparePartPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Brand::class      => BrandPolicy::class,
        Motorcycle::class => MotorcyclePolicy::class,
        Enquiry::class    => EnquiryPolicy::class,
        Service::class    => ServicePolicy::class,
        SparePart::class  => SparePartPolicy::class,
        User::class       => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}