<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\AdQuestionController;
use App\Http\Controllers\MessageController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
Route::get('/login', [LoginController::class, 'show'])->name('login');


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Ads pagina
Route::get('/advertenties', function () {
    return view('ads.ads');
})->name('ads');

// Contact pagina
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Profiel pagina
Route::get('/profiel', function () {
    return view('profile.profile');
})->name('profile.show');

// Mijn advertenties
Route::get('/mijn-advertenties', function () {
    return view('profile.my-ads');
})->name('profile.ads');

Route::get('/ads/create', [AdController::class, 'create'])->name('ads.create');
Route::post('/ads', [AdController::class, 'store'])->name('ads.store');




// Mijn aankopen
Route::get('/mijn-aankopen', function () {
    return view('profile.my-purchases');
})->name('profile.purchases');


Route::middleware(['auth'])->group(function () {
    Route::get('/advertentie-aanmaken', [AdController::class, 'create'])->name('ads.create');
    Route::post('/advertentie-aanmaken', [AdController::class, 'store'])->name('ads.store');
});

Route::get('/mijn-advertenties', [AdController::class, 'myAds'])->name('ads.my');

Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
Route::put('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');

Route::get('/profile/ads', [AdController::class, 'myAds'])->name('profile.ads');
Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');

Route::get('/advertenties', [AdController::class, 'index'])->name('ads.index');
Route::get('/ads/{ad}', [AdController::class, 'show'])->name('ads.show');
Route::get('/ads/{ad}/buy', [AdController::class, 'buy'])->name('ads.buy');
Route::post('/ads/{ad}/ask', [AdQuestionController::class, 'sendQuestion'])->name('ads.ask');
Route::post('/ads/{ad}/ask', [AdController::class, 'askQuestion'])->name('ads.ask')->middleware('auth');

Route::post('/ads/{ad}/ask', [AdQuestionController::class, 'sendQuestion'])->middleware('auth')->name('ads.ask');


Route::middleware('auth')->group(function () {
    Route::post('/ads/{ad}/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/profile/messages', [MessageController::class, 'index'])->name('profile.messages');
});

Route::get('/messages', [MessageController::class, 'index'])->middleware('auth')->name('messages.index');


Route::middleware('auth')->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index'); // overzicht
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show'); // detail gesprek
});

Route::post('messages/{conversation}', [MessageController::class, 'store'])->name('conversations.messages.store')->middleware('auth');



Route::post('/ads/{ad}/messages', [MessageController::class, 'storeFromAd'])
    ->name('ads.messages.store')
    ->middleware('auth');

    Route::post('messages/{conversation}', [MessageController::class, 'store'])->name('conversations.messages.store');
