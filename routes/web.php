<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Categories;
use App\Livewire\Foods;
use App\Livewire\Dashboard;
use App\Livewire\Positions;
use App\Livewire\Test;
use App\Livewire\Users;



Route::get('/login', function () {
    return redirect('/users');
})->name('login');


Route::get('/', Dashboard::class)->middleware('auth');
Route::get('/test', Test::class);
Route::get('/categories', Categories::class);
Route::get('/positions', Positions::class);
Route::get('/foods', Foods::class);
Route::get('/users', Users::class);


