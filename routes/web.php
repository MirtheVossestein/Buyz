<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\EnsureTwoFactorIsVerified;


// home
Route::get('/', [HomeController::class, 'index'])->name('home');

// login, register, logout
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// contact, cookie
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::post('/cookie-accept', fn() => redirect()->back()->withCookie(cookie('cookies_accepted', true, 60 * 24 * 30)))->name('cookie.accept');
Route::get('/cookiebeleid', fn() => view('cookie.info'))->name('cookie.info');

// ads kijken
Route::get('/advertenties', [AdController::class, 'index'])->name('ads.index');
Route::get('/ads/{ad}', [AdController::class, 'show'])->name('ads.show');
Route::get('/ads/{ad}/buy', [AdController::class, 'buy'])->name('ads.buy');

Route::post('/ads/{ad}/ask', [AdController::class, 'askQuestion'])->middleware('auth')->name('ads.ask');

Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
Route::post('/2fa', [TwoFactorController::class, 'store'])->name('2fa.store');




// alle auths routes

Route::middleware('auth')->group(function () {

    // profiel
    Route::get('/profiel', fn() => view('profile.profile'))->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/ads', [AdController::class, 'myAds'])->name('profile.ads');
    Route::get('/profile/messages', [MessageController::class, 'index'])->name('profile.messages');
    Route::get('/profiel/reviews', [ReviewController::class, 'index'])->name('profile.reviews.index');

    // ads beheren
    Route::get('/advertentie-aanmaken', [AdController::class, 'create'])->name('ads.create');
    Route::post('/advertentie-aanmaken', [AdController::class, 'store'])->name('ads.store');
    Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
    Route::put('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');
    Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');
    Route::patch('/ads/{advertentie}/update-status', [AdController::class, 'updateStatus'])->name('ads.updateStatus');

    // messages, gesprekken
    Route::post('/ads/{ad}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/ads/{ad}/messages', [MessageController::class, 'storeFromAd'])->name('ads.messages.store');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'store'])->name('conversations.messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'fetch'])->name('conversations.messages.fetch');
    Route::get('/notifications/message/{id}', [MessageController::class, 'openNotification'])->name('notifications.message');

    // reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});


Route::middleware(['auth', EnsureTwoFactorIsVerified::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


//admin
Route::middleware(['auth', IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // dashboard
        Route::get('dashboard', [AdminController::class, 'adminDashboard'])->name('dashboard');

        // ads beheren
        Route::get('ads/{ad}', [AdController::class, 'adminShow'])->name('ads.show');
        Route::put('ads/{ad}', [AdController::class, 'adminUpdate'])->name('ads.update');

        // admins beheren
        Route::post('make-admin/{user}', [AdminController::class, 'makeAdmin'])->name('make-admin');
        Route::post('remove-admin/{user}', [AdminController::class, 'removeAdmin'])->name('remove-admin');

        // reviews beheren
        Route::get('reviews', [AdminController::class, 'adminIndex'])->name('reviews.index');
        Route::get('reviews/{review}/edit', [AdminController::class, 'adminEdit'])->name('reviews.edit');
        Route::put('reviews/{review}', [AdminController::class, 'adminUpdate'])->name('reviews.update');
        Route::delete('reviews/{review}', [AdminController::class, 'adminDestroy'])->name('reviews.destroy');
    });

