<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;


Route::get('/nav', function () {
    return view('dashboard.navbar');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login'); 

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');
Route::resource('users', UserController::class);
Route::resource('products', ProductController::class);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Landing pages
Route::get('/home', [FrontController::class, 'index'])->name('home');
Route::get('/shopproducts', [FrontController::class, 'shopproducts'])->name('shopproducts');
Route::get('/shoping-cart', [FrontController::class, 'shopingcarts'])->name('shoping-cart');
Route::get('/blogs', [FrontController::class, 'blogs'])->name('blogs');
Route::get('/contact-us', [FrontController::class, 'contactUs'])->name('contact-us');
Route::get('/about-us', [FrontController::class, 'aboutUs'])->name('about-us');

// 


require __DIR__.'/auth.php';

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
