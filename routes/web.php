<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\CheckAdminMiddleware;
use App\Http\Middleware\TestMiddleware;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CatelogueController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Events\OrderCreated;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//#9VLLLRG8

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product-list', [HomeController::class, 'listProduct'])->name('product-list');

Route::get('product/{slug}', [ProductController::class, 'detail'])->name('product.detail');

// Mua bán hàng

Route::get('cart/list', [CartController::class, 'list'])->name('cart.list');
Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('checkout', [OrderController::class, 'index'])->name('checkout');
Route::post('order/save', [OrderController::class, 'save'])->name('order.save');

// // Routes công khai
Route::get('/login', [LoginController::class, 'showFormLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showFormRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');
});
// Routes admin được bảo vệ
Route::prefix('admin')
    ->as('admin.')
     // Thêm middleware auth và admin
    ->group(function () {
        Route::prefix('catalogues')
            ->as('catalogues.')
            ->group(function () {
                Route::get('/', [CatelogueController::class, 'index'])->name('index');
                Route::get('create', [CatelogueController::class, 'create'])->name('create');
                Route::post('store', [CatelogueController::class, 'store'])->name('store');
                Route::get('show/{id}', [CatelogueController::class, 'show'])->name('show');
                Route::get('edit/{id}', [CatelogueController::class, 'edit'])->name('edit');
                Route::put('update/{id}', [CatelogueController::class, 'update'])->name('update');
                Route::delete('destroy/{id}', [CatelogueController::class, 'destroy'])->name('destroy');
            });

        Route::resource('products', ProductController::class);
    });
