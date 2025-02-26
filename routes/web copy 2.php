<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\ShoppingCartController;
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
Route::get('/users/list', [UserController::class, 'getUsers'])->name('users.list');
Route::resource('products', ProductController::class);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/home', [FrontController::class, 'index'])->name('home');
Route::get('/shopproducts', [FrontController::class, 'shopproducts'])->name('shopproducts');
// Route::get('/shopping-cart', [FrontController::class, 'shoppingcarts'])->name('shopping-cart');
//  Route::post('/shopping-cart/{product}', [ShoppingCartController::class, 'addToCart'])->name('shopping-cart');
Route::get('/cart/count', [ShoppingCartController::class, 'cartCount']);

Route::post('/shopping-cart', [ShoppingCartController::class, 'updateCart'])->name('cart.update');
Route::get('/shopping-cart', [ShoppingCartController::class, 'index'])->name('shopping-cart');
Route::post('/cart/add', [ShoppingCartController::class, 'addToCart'])->name('cart.add');
// Route::post('/cart/update', [ShoppingCartController::class, 'updateCart'])->name('cart.update');
// Route::get('/cart', [ShoppingCartController::class, 'cartShow'])->name('cart.show');
Route::delete('/cart/remove/{id}', [ShoppingCartController::class, 'removeFromCart'])->name('cart.remove');
// Route::post('/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.create-intent');


// Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout'); 
// Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('payment.process');


// Route::post('/payment-intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.intent');
// Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
// Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

// Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
// Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
// Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
// Route::get('/payment-cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');

Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.create-intent');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

Route::post('/payment/intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.intent');

Route::get('/blogs', [FrontController::class, 'blogs'])->name('blogs');
Route::get('/contact-us', [FrontController::class, 'contactUs'])->name('contact-us');
Route::get('/about-us', [FrontController::class, 'aboutUs'])->name('about-us');

Route::resource('products', ProductController::class);
Route::get('/getcategories', [ProductController::class, 'getCategories'])->name('landing.categories');

Route::get('/landing-products', [ProductController::class, 'getLandingProducts'])->name('landing.products.data');


Route::post('/variants/store', [VariantController::class, 'store'])->name('variants.store');
Route::get('products/{productId}/variants', [VariantController::class, 'fetchVariants'])->name('variants.fetch');
Route::delete('/variants/{id}', [VariantController::class, 'destroy'])->name('variants.destroy');
Route::put('variants/{id}', [VariantController::class, 'update'])->name('variants.update');
// Route::get('/categories', [ProductController::class, 'getCategories'])->name('categories.get');


Route::resource('categories', ProductCategoryController::class);
Route::get('/test', function () {
    return view('test');
});

// Route::get('/categories', [ProductCategoryController::class, 'index'])->name('categories.index');          
// Route::get('/categories/create', [ProductCategoryController::class, 'create'])->name('categories.create');  
// Route::post('/categories', [ProductCategoryController::class, 'store'])->name('categories.store');         
// Route::get('/categories/{id}/edit', [ProductCategoryController::class, 'edit'])->name('categories.edit');   
// Route::put('/categories/{id}', [ProductCategoryController::class, 'update'])->name('categories.update');    
// Route::delete('/categories/{id}', [ProductCategoryController::class, 'destroy'])->name('categories.destroy'); 


require __DIR__ . '/auth.php';

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
