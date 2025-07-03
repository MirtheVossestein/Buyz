<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsAdmin;




//home 


Route::get('/', [HomeController::class, 'index'])->name('home');



// register

Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


//login 
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
Route::get('/login', [LoginController::class, 'show'])->name('login');

//logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ads pagina
Route::get('/advertenties', function () {
    return view('ads.ads');
})->name('ads');

// contact pagina
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// profiel pagina
Route::get('/profiel', function () {
    return view('profile.profile');
})->name('profile.show');

// mijn advertenties
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
Route::post('/ads/{ad}/ask', [AdController::class, 'askQuestion'])->name('ads.ask')->middleware('auth');



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


// asksysteem

Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
    ->name('messages.destroy')
    ->middleware('auth');

//status 
Route::patch('/ads/{ad}/status', [AdController::class, 'updateStatus'])->name('ads.updateStatus');


// notificaties
Route::get('/notifications/message/{id}', [MessageController::class, 'openNotification'])->name('notifications.message');


//reviews
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::middleware(['auth'])->group(function () {
    Route::get('/profiel/reviews', [ReviewController::class, 'index'])->name('profile.reviews.index');
});

Route::patch('/ads/{advertentie}/update-status', [AdController::class, 'updateStatus'])->name('ads.updateStatus');


// conversation
Route::get('/conversations/{conversation}/messages', [MessageController::class, 'fetch'])
    ->middleware('auth')
    ->name('conversations.messages.fetch');

Route::put('/admin/ads/{ad}', [AdController::class, 'adminUpdate'])->name('admin.ads.update');


// dashboard admin
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('/admin/make-admin/{user}', [AdminController::class, 'makeAdmin'])->name('admin.make-admin');
    Route::post('/admin/remove-admin/{user}', [AdminController::class, 'removeAdmin'])->name('admin.remove-admin');
    Route::put('/admin/ads/{ad}', [AdController::class, 'adminUpdate'])->name('admin.ads.update');
    Route::get('/admin/ads/{ad}', [AdController::class, 'adminShow'])->name('admin.ads.show');
    Route::resource('admin/reviews', ReviewController::class)->except(['create', 'store', 'show']);

});


// admin review beheer
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'adminDashboard'])->name('dashboard');

    Route::get('reviews', [AdminController::class, 'adminIndex'])->name('reviews.index');
    Route::get('reviews/{review}/edit', [AdminController::class, 'adminEdit'])->name('reviews.edit');
    Route::put('reviews/{review}', [AdminController::class, 'adminUpdate'])->name('reviews.update');
    Route::delete('reviews/{review}', [AdminController::class, 'adminDestroy'])->name('reviews.destroy');

    Route::post('make-admin/{user}', [AdminController::class, 'makeAdmin'])->name('make-admin');
    Route::post('remove-admin/{user}', [AdminController::class, 'removeAdmin'])->name('remove-admin');
});
