<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\xmlController;
use App\Http\Controllers\pageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\OrderController;

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

Route::get('/', function () {
    return view('templates.development')->with(['data' => ['include_view' => 'development.index']]);
})->middleware('auth.basic');
Route::get('/front', function () {
    return view('portal-welcome');
})->name('front')->middleware('auth.basic');
Route::get('/parsexml', function () {
    return view('templates.development')->with(['data' => ['include_view' => 'development.xml']]);
})->name('parseXml_Index')->middleware('auth.basic');

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login/attempt', [AuthController::class, 'attemptLogin'])->name('attempt_login');
Route::get('/account', [AuthController::class, 'showAccount'])->name('account')->middleware(['auth:h_users', 'verified']);
Route::get('/admins', [UserController::class, 'showAdmins'])->name('admins')->middleware('auth:h_users');
Route::get('/users', [UserController::class, 'showUsers'])->name('users')->middleware('auth:h_users');
Route::get('/users/{id}', [UserController::class, 'showUser'])->where('id', '[0-9]+')->middleware('auth:h_users');
Route::get('/users/new', [UserController::class, 'newUser'])->name('new_user')->middleware('auth:h_users');
Route::get('/admins/new', [UserController::class, 'newUser'])->name('new_admin')->middleware('auth:h_users');
// Route::get('/admins/{id}', [UserController::class, 'showAdmin'])->where('id', '[0-9]+')->middleware('auth:h_users');
// Route::get('/admins/new', [UserController::class, 'newAdmin'])->name('new_user')->middleware('auth:h_users');
Route::get('/orders', [OrderController::class, 'showOrders'])->name('orders')->middleware('auth:h_users');

Route::post('/user', [UserController::class, 'addUser']);
Route::put('/user', [UserController::class, 'updateUser']);
Route::delete('/user', [UserController::class, 'deleteUser']);

Route::get('/products', [ProductController::class, 'showProducts'])->name('products')->middleware('auth:h_users');
Route::get('/products/{id}', [ProductController::class, 'showProductDetails'])->where(['id' => '[0-9]+'])->middleware('auth:h_users');
Route::post('/ajax/products', [ProductController::class, 'getProducts'])->name('get_products')->middleware('auth:h_users');

Route::get('/basket', [BasketController::class, 'showBasket'])->name('basket')->middleware('auth:h_users');
Route::post('/basket', [BasketController::class, 'addToBasket'])->middleware('auth:h_users');
Route::put('/basket', [BasketController::class, 'updateBasket'])->middleware('auth:h_users');
Route::delete('/basket', [BasketController::class, 'deleteFromBasket'])->middleware('auth:h_users');

Route::post('/order', [OrderController::class, 'newOrder'])->middleware('auth:h_users');

Route::get('/parsexml/producten', [xmlController::class, 'importXml'])->defaults('type', 'producten')->name('parseXmlProducten')->middleware('auth.basic');
Route::get('/parsexml/klanten', [xmlController::class, 'importXml'])->defaults('type', 'klanten')->name('parseXmlKlanten')->middleware('auth.basic');
Route::get('/parsexml/voorraden', [xmlController::class, 'importXml'])->defaults('type', 'voorraden')->name('parseXmlVoorraden')->middleware('auth.basic');
Route::get('/parsexml/wmsorders', [xmlController::class, 'importXml'])->defaults('type', 'wmsorders')->name('parseXmlWmsorders')->middleware('auth.basic');

Route::post('/post/xml/klantUit', [xmlController::class, 'savePostedXml'])->defaults('xmltype', 'klant');
Route::post('/post/xml/artikelUit', [xmlController::class, 'savePostedXml'])->defaults('xmltype', 'artikel');
Route::post('/post/xml/orderUit', [xmlController::class, 'savePostedXml'])->defaults('xmltype', 'order');
Route::post('/post/xml/vrdstandUit', [xmlController::class, 'savePostedXml'])->defaults('xmltype', 'vrdstand');

Route::get('/post/xml/klantUit', function () { return abort(404); });
Route::get('/post/xml/artikelUit', function () { return abort(404); });
Route::get('/post/xml/orderUit', function () { return abort(404); });
Route::get('/post/xml/vrdstandUit', function () { return abort(404); });
