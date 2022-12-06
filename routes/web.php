<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\xmlController;
use App\Http\Controllers\pageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

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

// Route::get('/login', [authController::class, 'showLogin'])->name('login');
// Route::post('/login/attempt', [authController::class, 'attemptLogin'])->name('attempt_login');
Route::get('/account', [authController::class, 'showAccount'])->name('account')->middleware(['auth:h_users', 'verified']);
Route::get('/admins', [userController::class, 'showAdmins'])->name('admins')->middleware('auth:h_users');
Route::get('/users', [userController::class, 'showUsers'])->name('users')->middleware('auth:h_users');
Route::get('/users/{id}', [userController::class, 'showUser'])->where('id', '[0-9]+')->middleware('auth:h_users');
Route::get('/users/new', [userController::class, 'newUser'])->name('new_user')->middleware('auth:h_users');
Route::get('/admins/new', [userController::class, 'newUser'])->name('new_admin')->middleware('auth:h_users');
// Route::get('/admins/{id}', [userController::class, 'showAdmin'])->where('id', '[0-9]+')->middleware('auth:h_users');
// Route::get('/admins/new', [userController::class, 'newAdmin'])->name('new_user')->middleware('auth:h_users');
Route::post('/user', [userController::class, 'addUser']);
Route::put('/user', [userController::class, 'updateUser']);
Route::delete('/user', [userController::class, 'deleteUser']);

Route::get('/products', [productController::class, 'showProducts'])->name('products')->middleware('auth:h_users');
Route::get('/products/{id}', [productController::class, 'showProductDetails'])->where(['id' => '[0-9]+'])->middleware('auth:h_users');
Route::post('/products/{id}', [productController::class, 'addToBasket'])->where(['id' => '[0-9]+'])->middleware('auth:h_users');
Route::post('/ajax/products', [productController::class, 'getProducts'])->name('get_products')->middleware('auth:h_users');

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
