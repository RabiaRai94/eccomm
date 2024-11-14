<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProductCategoryController;

Route::get('/', function () {
    return view('landing.home');
});

Route::get('/login', function () {
    return view('admin.auth.login');
})->name('login'); 

Route::get('/register', function () {
    return view('admin.auth.register');
})->name('register');

Route::get('/dashboard', function () {
    return view('admin.dashboard.home');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::resource('users', UserController::class);
Route::get('/users/data', [UserController::class, 'getUsersData'])->name('users.data');
Route::resource('products', ProductController::class);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Landing pages
// In routes/web.php
  // Adjust based on your controller and method

Route::get('/home', [FrontController::class, 'index'])->name('home');
Route::get('/shopproducts', [FrontController::class, 'shopproducts'])->name('shopproducts');
Route::get('/shoping-cart', [FrontController::class, 'shopingcarts'])->name('shoping-cart');
Route::get('/blogs', [FrontController::class, 'blogs'])->name('blogs');
Route::get('/contact-us', [FrontController::class, 'contactUs'])->name('contact-us');
Route::get('/about-us', [FrontController::class, 'aboutUs'])->name('about-us');

Route::resource('products', ProductController::class);

Route::post('/variants/store', [VariantController::class, 'store'])->name('variants.store');

Route::get('/categories', [ProductController::class, 'getCategories'])->name('categories.get');



Route::get('/categories', [ProductCategoryController::class, 'index'])->name('categories.index');          
Route::get('/categories/create', [ProductCategoryController::class, 'create'])->name('categories.create');  
Route::post('/categories', [ProductCategoryController::class, 'store'])->name('categories.store');         
Route::get('/categories/{id}/edit', [ProductCategoryController::class, 'edit'])->name('categories.edit');   
Route::put('/categories/{id}', [ProductCategoryController::class, 'update'])->name('categories.update');    
Route::delete('/categories/{id}', [ProductCategoryController::class, 'destroy'])->name('categories.destroy'); 


require __DIR__.'/auth.php';

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
