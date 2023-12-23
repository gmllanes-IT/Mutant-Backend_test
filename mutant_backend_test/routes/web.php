<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\StripeController;

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

Route::group(['middleware' => 'prevent-back-history'], function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::middleware(['auth'])->group(function () {
        Route::get('profile', [UserController::class, 'show'])->name('profile');
        Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile/update', [UserController::class, 'update'])->name('profile.update');
        Route::post('/promote-to-admin', [UserController::class, 'promoteToAdmin'])->name('promote.to.admin');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/user-list', [AdminController::class, 'userList'])->name('user.list');
        Route::get('/users/{user}/edit', [UserController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::resource('products', ProductController::class);
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/products', [UserProductController::class, 'main'])->name('products.main');

        Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
        Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
        Route::patch('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeProduct'])->name('cart.remove');

        Route::get('/checkout', [StripeController::class, 'checkout'])->name('checkout');
        Route::post('/session', [StripeController::class, 'session'])->name('session');
        Route::get('/success', [StripeController::class, 'success'])->name('success');
    });
});
