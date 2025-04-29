<?php

use App\Models\Artist;
use App\Models\Artwork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\DashboardController;



Route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('exhibitions.index');
Route::get('/exhibitions/{exhibition}', [App\Http\Controllers\HomepageController::class, 'show'])->name('exhibitions.show');
Route::get('/about-us', [App\Http\Controllers\HomepageController::class, 'about'])->name('about');
Route::get('/permanent-collection', [App\Http\Controllers\HomepageController::class, 'permanent'])->name('about');

// Public routes
Route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('home');
Route::get('/future-exhibitions', [App\Http\Controllers\HomepageController::class, 'futureExhibitions'])->name('future.exhibitions');
Route::get('/current-exhibitions', [App\Http\Controllers\HomepageController::class, 'currentExhibitions'])->name('current.exhibitions');
Route::get('/past-exhibitions', [App\Http\Controllers\HomepageController::class, 'pastExhibitions'])->name('past.exhibitions');
Route::get('/static-exhibitions', [App\Http\Controllers\HomepageController::class, 'staticExhibitions'])->name('static.exhibitions');
Route::get('/exhibition', [App\Http\Controllers\HomepageController::class, 'Exhibition'])->name('exhibition');
Route::get('/collections', [App\Http\Controllers\HomepageController::class, 'Collections'])->name('collections');
Route::get('/plan', [App\Http\Controllers\HomepageController::class, 'Plan'])->name('plan');
Route::get('/access', [App\Http\Controllers\HomepageController::class, 'Access'])->name('access');
Route::get('/membership', [App\Http\Controllers\HomepageController::class, 'Membership'])->name('membership');
Route::get('/group', [App\Http\Controllers\HomepageController::class, 'Group'])->name('group');
Route::get('/ticket', [App\Http\Controllers\HomepageController::class, 'Ticket'])->name('ticket');
Route::get('/socially', [App\Http\Controllers\HomepageController::class, 'Socially'])->name('socially');
Route::get('/families', [App\Http\Controllers\HomepageController::class, 'Families'])->name('families');
Route::get('/young', [App\Http\Controllers\HomepageController::class, 'Young'])->name('young');
Route::get('/artist', [App\Http\Controllers\HomepageController::class, 'Artist'])->name('artist');
Route::get('/impact', [App\Http\Controllers\HomepageController::class, 'Impact'])->name('impact');
Route::get('/support', [App\Http\Controllers\HomepageController::class, 'Support'])->name('support');
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


// Email & User Verification
Route::get('user/v', [App\Http\Controllers\Auth\EmailVerificationController::class, 'emailVerify'])->name('email_verify');
Route::get('user/ver', [App\Http\Controllers\Auth\EmailVerificationController::class, 'userVerify'])->name('user_verify');
Route::get('/verify/{id}', [App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])->name('verify');
Route::post('/verify-code', [App\Http\Controllers\Auth\EmailVerificationController::class, 'verifyCode'])->name('verify.code');
Route::get('/resend-verification-code', [App\Http\Controllers\Auth\EmailVerificationController::class, 'resendVerificationCode'])->name('resend.verification');
Route::post('/skip-code', [App\Http\Controllers\Auth\EmailVerificationController::class, 'skipCode'])->name('skip.code');






// Authenticated user routes
Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::post('/update-password', [DashboardController::class, 'update'])->name('password.update');
    Route::post('/profile-update', [DashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::post('/kyc-submit', [DashboardController::class, 'submitKYC'])->name('kyc.submit');
    Route::get('/user-deposit', [App\Http\Controllers\User\DepositController::class, 'ShowDeposit'])->name('user.deposit');
    Route::get('/deposit', [App\Http\Controllers\User\DepositController::class, 'create'])->name('deposit.create');
    Route::post('/deposit', [App\Http\Controllers\User\DepositController::class, 'store'])->name('deposit.store');
    Route::get('/user-withdrawal', [DashboardController::class, 'ShowWithdrawal'])->name('user.withdrawal');
    Route::get('/add-exhibition', [App\Http\Controllers\User\ExhibitionController::class, 'index'])->name('user.create.exhibition');
    Route::post('/exhibitions', [App\Http\Controllers\User\ExhibitionController::class, 'store'])->name('exhibitions.store');
    Route::get('/future-exhibition', [App\Http\Controllers\User\ExhibitionController::class, 'futureExhibitions'])->name('user.future.exhibition');
    Route::get('/current-exhibition', [App\Http\Controllers\User\ExhibitionController::class, 'currentExhibitions'])->name('user.current.exhibition');
    Route::get('/exhibitions/{exhibition}', [App\Http\Controllers\User\ExhibitionController::class, 'showExhibition'])->name('user.exhibition.show');
    Route::post('/exhibitions/purchase', [App\Http\Controllers\User\ExhibitionController::class, 'purchase'])->name('user.exhibition.purchase');
    Route::get('/exhibitions/manage', [App\Http\Controllers\User\ExhibitionController::class, 'manageExhibition'])->name('user.exhibitions.manage');
    Route::get('/exhibitions/{exhibition}/edit', [App\Http\Controllers\User\ExhibitionController::class, 'edit'])->name('user.exhibitions.edit');
    Route::put('/exhibitions/{exhibition}', [App\Http\Controllers\User\ExhibitionController::class, 'update'])->name('user.exhibitions.update');
    Route::delete('/exhibitions/{exhibition}', [App\Http\Controllers\User\ExhibitionController::class, 'destroy'])->name('user.exhibitions.destroy');

    // Show KYC form
    Route::get('/kyc', [App\Http\Controllers\User\KycController::class, 'index'])->name('kyc.index');

    // Get KYC status (AJAX)
    Route::get('/kyc/status', [App\Http\Controllers\User\KycController::class, 'status'])->name('kyc.status');

    // Submit KYC (AJAX)
    Route::post('/kyc/submit', [App\Http\Controllers\User\KycController::class, 'submit'])->name('kyc.submit');
    // Withdrawal System Routes
    Route::prefix('withdrawals')->name('withdrawals.')->group(function () {
        Route::get('/', [App\Http\Controllers\User\WithdrawalController::class, 'index'])->name('index');
        Route::post('/request', [App\Http\Controllers\User\WithdrawalController::class, 'requestWithdrawal'])->name('request');
        Route::post('/link-account', [App\Http\Controllers\User\WithdrawalController::class, 'linkAccount'])->name('link-account');
    });
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


        Route::get('/change/user/password/page/{id}', [App\Http\Controllers\Admin\AdminController::class, 'showResetPasswordForm'])->name('admin.change.user.password.page');
        Route::post('/user-password-reset', [App\Http\Controllers\Admin\AdminController::class, 'resetPassword'])->name('admin.user.password_reset');

        Route::post('/admin/update-user', [App\Http\Controllers\Admin\AdminController::class, 'adminUpdateUser'])->name('admin.updateUser');
        Route::get('/reset-password/{user}', [App\Http\Controllers\Admin\AdminController::class, 'resetUserPassword'])->name('reset.password');
        Route::match(['get', 'post'], '/send-mail', [App\Http\Controllers\Admin\AdminController::class, 'sendMail'])->name('admin.send.mail');
        Route::get('/{user}/impersonate',  [App\Http\Controllers\Admin\AdminController::class, 'impersonate'])->name('users.impersonate');
        Route::get('/leave-impersonate',  [App\Http\Controllers\Admin\AdminController::class, 'leaveImpersonate'])->name('users.leave-impersonate');
        Route::get('/delete-user/{user}',  [App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('delete.user');
        Route::post('/update/total-balance', [App\Http\Controllers\Admin\BalanceController::class, 'updateTotalBalance'])->name('update.total.balance');



        // Users Routes
        Route::get('/users', [App\Http\Controllers\Admin\ManageUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\ManageUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [App\Http\Controllers\Admin\ManageUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\ManageUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\ManageUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\ManageUserController::class, 'destroy'])->name('admin.users.destroy');


        // User Deposits Routes
        Route::prefix('users/{user}/deposits')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ManageUserDepositController::class, 'index'])->name('admin.users.deposits.index');
            Route::get('/create', [App\Http\Controllers\Admin\ManageUserDepositController::class, 'create'])->name('admin.users.deposits.create');
            Route::post('/', [App\Http\Controllers\Admin\ManageUserDepositController::class, 'store'])->name('admin.users.deposits.store');
            Route::get('/{deposit}/edit', [App\Http\Controllers\Admin\ManageUserDepositController::class, 'edit'])->name('admin.users.deposits.edit');
            Route::put('/{deposit}', [App\Http\Controllers\Admin\ManageUserDepositController::class, 'update'])->name('admin.users.deposits.update');
            Route::delete('/{deposit}', [App\Http\Controllers\Admin\ManageUserDepositController::class, 'destroy'])->name('admin.users.deposits.destroy');
            Route::post('/{deposit}/approve', [App\Http\Controllers\Admin\ManageUserDepositController::class, 'approve'])->name('admin.users.deposits.approve');
            Route::post('/{deposit}/reject', [App\Http\Controllers\Admin\ManageUserDepositController::class, 'reject'])->name('admin.users.deposits.reject');
        });

        // Withdrawals Routes
        Route::prefix('users/{user}/withdrawals')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ManageUserWithdrawalController::class, 'index'])->name('admin.users.withdrawals.index');
            Route::get('/create', [App\Http\Controllers\Admin\ManageUserWithdrawalController::class, 'create'])->name('admin.users.withdrawals.create');
            Route::post('/', [App\Http\Controllers\Admin\ManageUserWithdrawalController::class, 'store'])->name('admin.users.withdrawals.store');
            Route::get('/{withdrawal}/edit', [App\Http\Controllers\Admin\ManageUserWithdrawalController::class, 'edit'])->name('admin.users.withdrawals.edit');
            Route::put('/{withdrawal}', [App\Http\Controllers\Admin\ManageUserWithdrawalController::class, 'update'])->name('admin.users.withdrawals.update');
            Route::delete('/{withdrawal}', [App\Http\Controllers\Admin\ManageUserWithdrawalController::class, 'destroy'])->name('admin.users.withdrawals.destroy');
            Route::post('/{withdrawal}/approve', [App\Http\Controllers\Admin\ManageUserWithdrawalController::class, 'approve'])->name('admin.users.withdrawals.approve');
            Route::post('/{withdrawal}/reject', [App\Http\Controllers\Admin\ManageUserWithdrawalController::class, 'reject'])->name('admin.users.withdrawals.reject');
        });



        // Identity Verification Routes
        Route::prefix('users/{user}/identity-verifications')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ManageUserIdentityVerificationController::class, 'index'])->name('admin.users.identity-verifications.index');
            Route::get('/{verification}', [App\Http\Controllers\Admin\ManageUserIdentityVerificationController::class, 'show'])->name('admin.users.identity-verifications.show');
            Route::post('/{verification}/approve', [App\Http\Controllers\Admin\ManageUserIdentityVerificationController::class, 'approve'])->name('admin.users.identity-verifications.approve');
            Route::post('/{verification}/reject', [App\Http\Controllers\Admin\ManageUserIdentityVerificationController::class, 'reject'])->name('admin.users.identity-verifications.reject');
            Route::delete('/{id}', [App\Http\Controllers\Admin\ManageUserIdentityVerificationController::class, 'destroy'])
                ->name('admin.users.identity-verifications.destroy');
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

 
        // routes/web.php
        Route::prefix('admin/future-exhibitions')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\FutureExhibitionController::class, 'index'])->name('admin.future-exhibitions.index');
            Route::get('/create', [App\Http\Controllers\Admin\FutureExhibitionController::class, 'create'])->name('admin.future-exhibitions.create');
            Route::post('/', [App\Http\Controllers\Admin\FutureExhibitionController::class, 'store'])->name('admin.future-exhibitions.store');
            Route::get('/{exhibition}/edit', [App\Http\Controllers\Admin\FutureExhibitionController::class, 'edit'])->name('admin.future-exhibitions.edit');
            Route::put('/{exhibition}', [App\Http\Controllers\Admin\FutureExhibitionController::class, 'update'])->name('admin.future-exhibitions.update');
            Route::delete('/{exhibition}', [App\Http\Controllers\Admin\FutureExhibitionController::class, 'destroy'])->name('admin.future-exhibitions.destroy');
        });


        // Bid Management Routes
        Route::group(['prefix' => 'bids', 'as' => 'bids.'], function () {
            Route::get('/', [App\Http\Controllers\Admin\ManageBidController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\ManageBidController::class, 'store'])->name('store');
            Route::get('/{bid}/edit', [App\Http\Controllers\Admin\ManageBidController::class, 'edit'])->name('edit');
            Route::put('/{bid}', [App\Http\Controllers\Admin\ManageBidController::class, 'update'])->name('update');
            Route::patch('/{bid}/approve', [App\Http\Controllers\Admin\ManageBidController::class, 'approve'])->name('approve');
            Route::delete('/{bid}', [App\Http\Controllers\Admin\ManageBidController::class, 'destroy'])->name('destroy');
        });
    });
});
