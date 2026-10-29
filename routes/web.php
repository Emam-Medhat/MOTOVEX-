<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TestRideController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\MotorcycleController as AdminMotorcycleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EnquiryController;

// ─── Public Routes ─────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brand:slug}', [BrandController::class, 'show'])->name('brands.show');
Route::get('/motorcycles', [MotorcycleController::class, 'index'])->name('motorcycles.index');
Route::get('/motorcycles/{motorcycle:slug}', [MotorcycleController::class, 'show'])->name('motorcycles.show');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ─── Test Ride Requests (homepage mini-form) ───────────────────────────────────
Route::post('/test-ride', [TestRideController::class, 'store'])
    ->middleware('throttle:5,1')   // 5 requests per minute per IP
    ->name('test-ride.store');

// ─── Cart ───────────────────────────────────────────────────────────────────────
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{motorcycle:slug}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{motorcycle:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/checkout', [CartController::class, 'placeOrder'])->name('cart.place-order');
Route::get('/cart/order-success', [CartController::class, 'orderSuccess'])->name('cart.order-success');

// ─── Authentication ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:register');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.store');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ─── Customer Account ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/enquiries', [AccountController::class, 'enquiries'])->name('enquiries');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{enquiry}', [AccountController::class, 'orderShow'])->name('orders.show');
    Route::get('/profile', [AccountController::class, 'editProfile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/security', [AccountController::class, 'editSecurity'])->name('security');
    Route::put('/security', [AccountController::class, 'updatePassword'])->name('security.update');
});

use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ShowroomController;
use App\Http\Controllers\Admin\TestRideController as AdminTestRideController;

// ─── Admin / Dashboard ──────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active', 'role:admin,data_entry,sales_agent'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Catalogue – admin + data entry only
    Route::middleware('role:admin,data_entry')->group(function () {
        Route::resource('brands', AdminBrandController::class);
        Route::get('brands/{brand}/assign-users', [AdminBrandController::class, 'assignUsers'])->name('brands.assign-users');
        Route::post('brands/{brand}/sync-users',  [AdminBrandController::class, 'syncUsers'])->name('brands.sync-users');

        Route::resource('motorcycles', AdminMotorcycleController::class);
        Route::delete('motorcycles/bulk/destroy', [AdminMotorcycleController::class, 'bulkDestroy'])->name('motorcycles.bulk-destroy');
        Route::resource('promotions', PromotionController::class);
        Route::resource('showrooms', ShowroomController::class);
    });

    // Users – admin only
    Route::resource('users', UserController::class)->middleware('role:admin');
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])
        ->middleware('role:admin')->name('users.toggle-active');

    // Enquiries – admin (full) + sales agent (view & update status)
    Route::get('enquiries/export', [EnquiryController::class, 'export'])
        ->middleware('role:admin,sales_agent')->name('enquiries.export');
    Route::resource('enquiries', EnquiryController::class)
        ->only(['index', 'show', 'update'])
        ->middleware('role:admin,sales_agent');
    Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])
        ->middleware('role:admin')->name('enquiries.destroy');

    // Purchase Orders — admin + sales agent
    Route::middleware('role:admin,sales_agent')->prefix('orders')->name('orders.')->group(function () {
        Route::get('/',                                   [EnquiryController::class, 'ordersIndex'])->name('index');
        Route::get('/{enquiry}',                          [EnquiryController::class, 'orderShow'])->name('show');
        Route::patch('/{enquiry}/status',                 [EnquiryController::class, 'orderUpdateStatus'])->name('update-status');
        Route::patch('/{enquiry}/payment',                [EnquiryController::class, 'orderVerifyPayment'])->name('verify-payment');
    });
    Route::delete('orders/{enquiry}', [EnquiryController::class, 'orderDestroy'])
        ->middleware('role:admin')->name('orders.destroy');

    // Test Ride Bookings – admin + sales agent (view & update); admin-only delete
    Route::middleware('role:admin,sales_agent')->group(function () {
        Route::get('test-rides',                               [AdminTestRideController::class, 'index'])->name('test-rides.index');
        Route::get('test-rides/{testRide}',                    [AdminTestRideController::class, 'show'])->name('test-rides.show');
        Route::put('test-rides/{testRide}',                    [AdminTestRideController::class, 'update'])->name('test-rides.update');
        Route::patch('test-rides/{testRide}/status',           [AdminTestRideController::class, 'updateStatus'])->name('test-rides.update-status');
    });
    Route::delete('test-rides/{testRide}',                     [AdminTestRideController::class, 'destroy'])
        ->middleware('role:admin')->name('test-rides.destroy');
});

// Convenience redirect: sends each account type to its own home.
Route::get('/dashboard', function () {
    return auth()->user()->isStaff()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('account.dashboard');
})->middleware(['auth', 'active'])->name('dashboard');
