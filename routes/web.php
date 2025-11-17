<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Models\User;

use App\Livewire\Categories;
use App\Livewire\Foods;
use App\Livewire\Dashboard;
use App\Livewire\Positions;
use App\Livewire\Test;
use App\Livewire\Users;

Route::get('/users', Users::class);
Route::get('/test', Test::class);

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }
    return redirect('/users');
})->name('login');


// A. verification.notice (صفحه اطلاع‌رسانی)
Route::get('/email/verify', function () {
    // اگر کاربر تایید شده باشد، به صفحه اصلی برگرداند.
    if (Auth::user() && Auth::user()->hasVerifiedEmail()) {
        return redirect('/');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// B. verification.verify (لینک کلیک شده)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

// C. verification.resend (ارسال مجدد)
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

//Route::get('/', Dashboard::class)->middleware('auth');

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/', Dashboard::class);
    Route::get('/categories', Categories::class);
    Route::get('/positions', Positions::class);
    Route::get('/foods', Foods::class);

});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    session()->regenerate();
    session()->regenerateToken();
    return redirect('/users');
})->middleware('auth')->name('logout');


