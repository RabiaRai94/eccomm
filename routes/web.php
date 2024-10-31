<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('landing.home');  
})->name('home');


Route::get('/contact-us', function () {
    return view('landing.contact-us');
})->name('contact');
Route::get('/products', function () {return view('landing.products');})->name('products');
Route::get('/about-us', function () {
    return view('landing.about-us');
})->name('about-us'); 
Route::get('/blogs', function () {
    return view('landing.blogs');
})->name('blogs');
Route::get('/shoping-cart', function () {
    return view('landing.shoping-cart');
})->name('shoping-cart');
Route::get('/dashboard', function () {
    return view('dashboard.sidebar');
})->name('dashboard');

