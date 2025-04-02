<?php

use App\Models\Artist;
use App\Models\Artwork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\DashboardController;



Route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('exhibitions.index');
Route::get('/exhibitions/{exhibition}', [App\Http\Controllers\HomepageController::class, 'show'])->name('exhibitions.show');

// Public routes
Route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('home');
Route::get('/exhibitions', [App\Http\Controllers\HomepageController::class, 'index'])->name('exhibitions.index');
Route::get('/exhibitions/{exhibition}', [App\Http\Controllers\HomepageController::class, 'show'])->name('exhibitions.show');
Route::get('/artworks/{artwork}', [App\Http\Controllers\HomepageController::class, 'showArtwork'])->name('artwork.show');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Auction routes
    Route::post('/artworks/{artwork}/bid', [App\Http\Controllers\HomepageController::class, 'placeBid'])->name('artwork.bid');
    Route::get('/auctions/{artwork}', [App\Http\Controllers\HomepageController::class, 'liveAuction'])->name('auction.live');

    // Purchase routes
    Route::post('/artworks/{artwork}/purchase', [App\Http\Controllers\HomepageController::class, 'purchaseArtwork'])->name('artwork.purchase');
    Route::get('/purchase/{artwork}/confirmation', function (Artwork $artwork) {
        return view('exhibitions.purchase-confirmation', ['artwork' => $artwork]);
    })->name('purchase.confirmation');

    // Artist contact (reveal email)
    Route::get('/artist/{artist}/contact', function (Artist $artist) {
        return response()->json(['email' => $artist->email]);
    })->name('artist.contact');
});




// Login and registration routes (only accessible to guests)
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login.page');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.submit');
});


Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('user.logout');






// Authenticated user routes
Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::post('/update-password', [DashboardController::class, 'update'])->name('password.update');
    Route::post('/profile-update', [DashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::post('/kyc-submit', [DashboardController::class, 'submitKYC'])->name('kyc.submit');
});

Route::get('/user/add-exhibition', function(){
    return view('user.create-exhibition');
});
Route::get('/user/exhibitions', function(){
    return view('user.exhibitions');
});




Route::get('admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'adminLoginForm'])->name('admin.login');
Route::post('admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->name('login.submit');



// Admin Routes
Route::prefix('admin')->group(function () {
    Route::post('logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('logout');

    // Protecting admin routes using the 'admin' middleware
    Route::middleware(['admin'])->group(function () { // Admin Profile Routes
        Route::get('/home', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.home');
        // Dashboard Routes

        Route::get('/dashboard/analytics', [App\Http\Controllers\Admin\AdminController::class, 'getAnalytics'])->name('admin.dashboard.analytics');


        // User Management Routes
        Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.users.index');
        // ... other user routes

        // Exhibition Management Routes
        Route::get('/exhibitions', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.exhibitions.index');
        // ... other exhibition routes

        // Withdrawal Management Routes
        Route::get('/withdrawals', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.withdrawals.index');
        // ... other withdrawal routes

        // Settings Route
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('admin.settings');

        // Admin User Routes
        Route::prefix('users')->group(function () {
            // manage user CRUD routes
            Route::get('/', [App\Http\Controllers\Admin\ManageUserController::class, 'index'])->name('admin.users.index');
            Route::get('/users/create', [App\Http\Controllers\Admin\ManageUserController::class, 'create'])->name('admin.users.create');
            Route::post('/users', [App\Http\Controllers\Admin\ManageUserController::class, 'store'])->name('admin.users.store');
            Route::get('/users/{user}', [App\Http\Controllers\Admin\ManageUserController::class, 'show'])->name('admin.user.view');

            // AJAX Routes
            Route::get('/getusers', [App\Http\Controllers\Admin\ManageUserController::class, 'getUsers'])->name('admin.getusers');
            Route::post('/users/toggle-status', [App\Http\Controllers\Admin\ManageUserController::class, 'toggleUserStatus'])->name('admin.user.toggleUserStatus');
            Route::post('/users/toggle-email-status', [App\Http\Controllers\Admin\ManageUserController::class, 'toggleEmailStatus'])->name('admin.user.toggleEmailStatus');
            Route::post('/users/send-mass-email', [App\Http\Controllers\Admin\ManageUserController::class, 'sendMassEmail'])->name('admin.users.sendMassEmail');
        });


        // Admin Exhibition Routes
        Route::prefix('exhibitions')->group(function () {
            // Exhibition CRUD routes
            Route::get('/', [App\Http\Controllers\Admin\ExhibitionController::class, 'index'])->name('admin.exhibitions.index');
            Route::get('/{exhibition}/view', [App\Http\Controllers\Admin\ExhibitionController::class, 'view'])
                ->name('admin.exhibition.view');
            Route::get('/create', [App\Http\Controllers\Admin\ExhibitionController::class, 'create'])->name('admin.exhibitions.create');
            Route::post('/', [App\Http\Controllers\Admin\ExhibitionController::class, 'store'])->name('admin.exhibitions.store');
            Route::get('/{exhibition}', [App\Http\Controllers\Admin\ExhibitionController::class, 'show'])->name('admin.exhibitions.show');
            Route::get('/{exhibition}/edit', [App\Http\Controllers\Admin\ExhibitionController::class, 'edit'])->name('admin.exhibitions.edit');
            Route::put('/{exhibition}', [App\Http\Controllers\Admin\ExhibitionController::class, 'update'])->name('admin.exhibitions.update');
            Route::put('/{exhibition}/status', [App\Http\Controllers\Admin\ExhibitionController::class, 'updateStatus'])->name('admin.exhibitions.update-status');
            Route::delete('/{exhibition}', [App\Http\Controllers\Admin\ExhibitionController::class, 'destroy'])->name('admin.exhibitions.destroy');

            // AJAX route for exhibitions table
            Route::get('/getexhibitions', [App\Http\Controllers\Admin\ExhibitionController::class, 'getExhibitions'])->name('admin.getexhibitions');
        });
    });
});
