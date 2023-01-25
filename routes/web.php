<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/add-product', [HomeController::class, 'add_product']);
Route::get('/add-loved', [HomeController::class, 'add_loved']);
Route::get('/cart', [HomeController::class, 'cart']);
Route::get('/inc-quantity', [HomeController::class, 'inc_quantity']);
Route::get('/dec-quantity', [HomeController::class, 'dec_quantity']);
Route::get('/del-product', [HomeController::class, 'del_product']);
Route::prefix('/shop')->group(function () {
    Route::get('/', [HomeController::class, 'shop'])->name('shop');
    Route::get('/item/{id}', [HomeController::class, 'item'])->name('shop.item');
    Route::post('/item/review/{id}', [HomeController::class, 'review'])->name('shop.review');
});
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'sendMessage']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'can:is_admin'])->prefix('/admin')->group(function () {
    Route::resource('products', ProductsController::class);
    Route::resource('categories', CategoriesController::class);
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::resource('orders', OrdersController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/create', [OrdersController::class, 'checkoutpost'])->name('checkoutpost');
});
