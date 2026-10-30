<?php

use Illuminate\Support\Facades\Route;

// ── Public controllers ────────────────────────────────────────────────────────
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SparePartsController;
use App\Http\Controllers\PromotionController as PublicPromotionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TestRideController;
use App\Http\Controllers\RentalRequestController;

// ── Auth controllers ──────────────────────────────────────────────────────────
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// ── Account (customer / merchant) controllers ─────────────────────────────────
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\SparePartController as MerchantSparePartController;

// ── Admin controllers ─────────────────────────────────────────────────────────
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\MotorcycleController as AdminMotorcycleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SparePartController as AdminSparePartController;
use App\Http\Controllers\Admin\ShowroomController;
use App\Http\Controllers\Admin\TestRideController as AdminTestRideController;

// ─── Public Routes ─────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brand:slug}', [BrandController::class, 'show'])->name('brands.show');
Route::get('/motorcycles', [MotorcycleController::class, 'index'])->name('motorcycles.index');
Route::get('/motorcycles/{motorcycle:slug}', [MotorcycleController::class, 'show'])->name('motorcycles.show');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/promotions', [PublicPromotionController::class, 'index'])->name('promotions');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ─── Spare Parts (Public) ──────────────────────────────────────────────────────
Route::get('/spare-parts', [SparePartsController::class, 'index'])->name('spare-parts.index');
Route::get('/spare-parts/{sparePart:slug}', [SparePartsController::class, 'show'])->name('spare-parts.show');

// ─── Test Ride / Rental Requests ──────────────────────────────────────────────
Route::post('/test-ride', [TestRideController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('test-ride.store');
Route::post('/motorcycles/{motorcycle:slug}/rent', [RentalRequestController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('rentals.store');

// ─── Cart ──────────────────────────────────────────────────────────────────────
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{motorcycle:slug}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{motorcycle:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Spare parts cart routes
Route::post('/cart/add-part/{sparePart:slug}', [CartController::class, 'addSparePart'])->name('cart.add-part');
Route::patch('/cart/update-part/{sparePart:slug}', [CartController::class, 'updateSparePart'])->name('cart.update-part');
Route::delete('/cart/remove-part/{sparePart:slug}', [CartController::class, 'removeSparePart'])->name('cart.remove-part');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/checkout', [CartController::class, 'placeOrder'])->name('cart.place-order');
    Route::get('/cart/order-success', [CartController::class, 'orderSuccess'])->name('cart.order-success');
});

// ─── Authentication ────────────────────────────────────────────────────────────
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

// Merchant pending page
Route::get('/register/pending', [RegisterController::class, 'showPendingPage'])->name('register.pending');
Route::get('/merchant/pending', [RegisterController::class, 'showPendingPage'])
    ->middleware('auth')
    ->name('merchant.pending');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ─── Customer / Merchant Account ──────────────────────────────────────────────
Route::middleware(['auth', 'active'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/enquiries', [AccountController::class, 'enquiries'])->name('enquiries');
    // Customer order tracking (canonical orders created at checkout)
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderShow'])->name('orders.show');
    Route::get('/profile', [AccountController::class, 'editProfile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/security', [AccountController::class, 'editSecurity'])->name('security');
    Route::put('/security', [AccountController::class, 'updatePassword'])->name('security.update');

    // ── Merchant Spare Parts ────────────────────────────────────────────────
    Route::prefix('spare-parts')->name('spare-parts.')->group(function () {
        Route::get('/',                  [MerchantSparePartController::class, 'index'])->name('index');
        Route::get('/create',            [MerchantSparePartController::class, 'create'])->name('create');
        Route::post('/',                 [MerchantSparePartController::class, 'store'])->name('store');
        Route::get('/{sparePart}/edit',  [MerchantSparePartController::class, 'edit'])->name('edit');
        Route::put('/{sparePart}',       [MerchantSparePartController::class, 'update'])->name('update');
        Route::delete('/{sparePart}',    [MerchantSparePartController::class, 'destroy'])->name('destroy');
    });

    // ── Merchant Orders (Spare Parts Fulfillment) ────────────────────────────
    // Kept on a dedicated prefix so it never collides with the customer
    // order-tracking routes (/account/orders).
    Route::prefix('merchant-orders')->name('merchant-orders.')->group(function () {
        Route::get('/',                           [\App\Http\Controllers\Account\OrderController::class, 'index'])->name('index');
        Route::get('/{item}',                     [\App\Http\Controllers\Account\OrderController::class, 'show'])->name('show');
        Route::patch('/{item}/status',            [\App\Http\Controllers\Account\OrderController::class, 'updateStatus'])->name('update-status');
    });
});

// ─── Admin / Dashboard ─────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active', 'role:admin,data_entry,sales_agent'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('requests', [RequestController::class, 'index'])->name('requests.index');

    // Catalogue – admin + data entry only
    Route::middleware('role:admin,data_entry')->group(function () {
        Route::resource('brands', AdminBrandController::class);
        Route::get('brands/{brand}/assign-users', [AdminBrandController::class, 'assignUsers'])->name('brands.assign-users');
        Route::post('brands/{brand}/sync-users',  [AdminBrandController::class, 'syncUsers'])->name('brands.sync-users');

        Route::resource('motorcycles', AdminMotorcycleController::class);
        Route::delete('motorcycles/bulk/destroy', [AdminMotorcycleController::class, 'bulkDestroy'])->name('motorcycles.bulk-destroy');
        Route::resource('promotions', PromotionController::class);
        Route::resource('showrooms', ShowroomController::class);

        // Services CMS
        Route::patch('services/{service}/toggle', [AdminServiceController::class, 'toggleActive'])->name('services.toggle');
        Route::resource('services', AdminServiceController::class);

        // Spare Parts CMS
        Route::patch('spare-parts/{sparePart}/toggle-publish', [AdminSparePartController::class, 'togglePublish'])->name('spare-parts.toggle-publish');
        Route::resource('spare-parts', AdminSparePartController::class);
    });

    // Users – admin only
    Route::resource('users', UserController::class)->middleware('role:admin');
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])
        ->middleware('role:admin')->name('users.toggle-active');
    Route::patch('users/{user}/approve-merchant', [UserController::class, 'approveMerchant'])
        ->middleware('role:admin')->name('users.approve-merchant');
    Route::patch('users/{user}/reject-merchant', [UserController::class, 'rejectMerchant'])
        ->middleware('role:admin')->name('users.reject-merchant');

    // Enquiries
    Route::get('enquiries/export', [EnquiryController::class, 'export'])
        ->middleware('role:admin,data_entry,sales_agent')->name('enquiries.export');
    Route::resource('enquiries', EnquiryController::class)
        ->only(['index', 'show', 'update'])
        ->middleware('role:admin,data_entry,sales_agent');
    Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])
        ->middleware('role:admin')->name('enquiries.destroy');

    // Orders (enquiry-based)
    Route::middleware('role:admin,data_entry,sales_agent')->prefix('orders')->name('orders.')->group(function () {
        Route::get('/',              [EnquiryController::class, 'ordersIndex'])->name('index');
        Route::get('/{enquiry}',     [EnquiryController::class, 'orderShow'])->name('show');
        Route::patch('/{enquiry}/status',  [EnquiryController::class, 'orderUpdateStatus'])->name('update-status');
        Route::patch('/{enquiry}/payment', [EnquiryController::class, 'orderVerifyPayment'])->name('verify-payment');
    });

    // Sales (cart-based orders)
    Route::middleware('role:admin,data_entry')->prefix('sales')->name('sales.')->group(function () {
        Route::get('/',                           [OrderController::class, 'index'])->name('index');
        Route::get('/{order}',                    [OrderController::class, 'show'])->name('show');
        Route::patch('/items/{item}/status',      [OrderController::class, 'updateItemStatus'])->name('items.update-status');
        Route::patch('/{order}/verify-payment',   [OrderController::class, 'verifyPayment'])->name('verify-payment');
    });
    Route::delete('orders/{enquiry}', [EnquiryController::class, 'orderDestroy'])
        ->middleware('role:admin')->name('orders.destroy');

    // Test Rides
    Route::middleware('role:admin,data_entry,sales_agent')->group(function () {
        Route::get('test-rides',                         [AdminTestRideController::class, 'index'])->name('test-rides.index');
        Route::get('test-rides/{testRide}',              [AdminTestRideController::class, 'show'])->name('test-rides.show');
        Route::put('test-rides/{testRide}',              [AdminTestRideController::class, 'update'])->name('test-rides.update');
        Route::patch('test-rides/{testRide}/status',     [AdminTestRideController::class, 'updateStatus'])->name('test-rides.update-status');
    });
    Route::middleware('role:admin,data_entry,sales_agent')->prefix('rentals')->name('rentals.')->group(function () {
        Route::get('/',              [EnquiryController::class, 'rentalsIndex'])->name('index');
        Route::get('/{enquiry}',     [EnquiryController::class, 'orderShow'])->name('show');
        Route::patch('/{enquiry}/status', [EnquiryController::class, 'orderUpdateStatus'])->name('update-status');
    });
    Route::delete('test-rides/{testRide}', [AdminTestRideController::class, 'destroy'])
        ->middleware('role:admin')->name('test-rides.destroy');
});

// Redirect /dashboard based on role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isStaff()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->isPendingMerchant()) {
        return redirect()->route('merchant.pending');
    }
    if ($user->isRejectedMerchant()) {
        return redirect()->route('login')->withErrors([
            'email' => 'Your merchant application has been rejected.',
        ]);
    }

    return redirect()->route('account.dashboard');
})->middleware(['auth', 'active'])->name('dashboard');
