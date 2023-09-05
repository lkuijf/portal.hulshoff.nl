<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\xmlController;
use App\Http\Controllers\pageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TilesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\AddressController;

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

// Route::get('/', function () {
//     return view('templates.development')->with(['data' => ['include_view' => 'development.index']]);
// })->middleware('auth.basic');
// Route::get('/front', function () {
//     return view('portal-welcome');
// })->name('front')->middleware('auth.basic');
// Route::get('/parsexml', function () {
//     return view('templates.development')->with(['data' => ['include_view' => 'development.xml']]);
// })->name('parseXml_Index')->middleware('auth.basic');

Route::get('/', function () {
    return redirect('login');
});

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login/attempt', [AuthController::class, 'attemptLogin'])->name('attempt_login');
Route::get('/account', [AuthController::class, 'showAccount'])->name('account')->middleware(['auth:h_users', 'verified']);
Route::get('/users', [UserController::class, 'showUsers'])->name('users')->middleware('auth:h_users');
Route::get('/users/{id}', [UserController::class, 'showUser'])->name('user_detail')->where('id', '[0-9]+')->middleware('auth:h_users');
Route::get('/users/new', [UserController::class, 'newUser'])->name('new_user')->middleware('auth:h_users');
Route::get('/admins', [UserController::class, 'showUsers'])->name('admins')->middleware('auth:h_users');
Route::get('/admins/{id}', [UserController::class, 'showUser'])->name('admin_detail')->where('id', '[0-9]+')->middleware('auth:h_users');
Route::get('/admins/new', [UserController::class, 'newUser'])->name('new_admin')->middleware('auth:h_users');
// Route::get('/admins/{id}', [UserController::class, 'showAdmin'])->where('id', '[0-9]+')->middleware('auth:h_users');
// Route::get('/admins/new', [UserController::class, 'newAdmin'])->name('new_user')->middleware('auth:h_users');
Route::get('/orders', [OrderController::class, 'showOrders'])->defaults('type', 'confirmed')->name('orders')->middleware('auth:h_users');
Route::post('/orders', [OrderController::class, 'showOrders'])->defaults('type', 'confirmed')->middleware('auth:h_users');
Route::get('/orders/{id}', [OrderController::class, 'showOrder'])->defaults('type', 'confirmed')->name('order_detail')->where('id', '[0-9]+')->middleware('auth:h_users');
Route::get('/reservations', [OrderController::class, 'showOrders'])->defaults('type', 'reserved')->name('reservations')->middleware('auth:h_users');
Route::post('/reservations', [OrderController::class, 'showOrders'])->defaults('type', 'reserved')->middleware('auth:h_users');
Route::get('/reservations/{id}', [OrderController::class, 'showOrder'])->defaults('type', 'reserved')->name('reservation_detail')->where('id', '[0-9]+')->middleware('auth:h_users');

Route::get('/tiles', [TilesController::class, 'showTiles'])->name('tiles')->middleware('auth:h_users');
Route::post('/tile', [TilesController::class, 'uploadTile'])->name('tile_upload')->middleware('auth:h_users');
Route::delete('/tile', [TilesController::class, 'deleteTile'])->name('tile_delete')->middleware('auth:h_users');

Route::get('/reports', [ReportController::class, 'reportsPage'])->name('reports')->middleware('auth:h_users');
Route::get('/manuals', [ManualController::class, 'manualsPage'])->name('manuals')->middleware('auth:h_users');
Route::get('/manuals/new', [ManualController::class, 'showNewManual'])->name('new_manual')->middleware('auth:h_users');
Route::get('/manuals/{id}', [ManualController::class, 'showManual'])->name('manual_detail')->middleware('auth:h_users');
Route::get('/addresses', [AddressController::class, 'addressesPage'])->name('addresses')->middleware('auth:h_users');
Route::get('/addresses/new', [AddressController::class, 'showNewAddress'])->name('new_address')->middleware('auth:h_users');
Route::get('/addresses/{id}', [AddressController::class, 'showAddress'])->name('address_detail')->middleware('auth:h_users');
Route::post('/validate-report', [ReportController::class, 'validateReport'])->name('validate_report')->middleware('auth:h_users');

Route::post('/user', [UserController::class, 'addUser']);
Route::put('/user', [UserController::class, 'updateUser']);
Route::delete('/user', [UserController::class, 'deleteUser']);

Route::post('/manual', [ManualController::class, 'storeManual']);
Route::put('/manual', [ManualController::class, 'storeManual']);
Route::delete('/manual', [ManualController::class, 'deleteManual']);

Route::post('/address', [AddressController::class, 'storeAddress']);
Route::put('/address', [AddressController::class, 'storeAddress']);
Route::delete('/address', [AddressController::class, 'deleteAddress']);

Route::get('/products', [ProductController::class, 'showProducts'])->name('products')->middleware('auth:h_users');
Route::get('/products#tiles', [ProductController::class, 'showProducts'])->name('products_tiles')->middleware('auth:h_users');
Route::get('/products/{id}', [ProductController::class, 'showProductDetails'])->name('product_detail')->where(['id' => '[0-9]+'])->middleware('auth:h_users');

Route::get('/basket', [BasketController::class, 'showBasket'])->name('basket')->middleware('auth:h_users');
Route::post('/basket', [BasketController::class, 'addToBasket'])->middleware('auth:h_users');
Route::put('/basket', [BasketController::class, 'updateBasket'])->middleware('auth:h_users');
Route::delete('/basket', [BasketController::class, 'deleteFromBasket'])->middleware('auth:h_users');

Route::post('/order', [OrderController::class, 'newOrder'])->middleware('auth:h_users');
Route::put('/order', [OrderController::class, 'updateOrder'])->middleware('auth:h_users');
Route::delete('/order', [OrderController::class, 'deleteOrder'])->middleware('auth:h_users');

Route::put('/order-article', [OrderController::class, 'updateOrderArticle'])->middleware('auth:h_users');
Route::delete('/order-article', [OrderController::class, 'deleteOrderArticle'])->middleware('auth:h_users');

Route::post('/ajax/products', [ProductController::class, 'getProducts'])->name('get_products')->middleware('auth:h_users');
Route::post('/ajax/types', [ProductController::class, 'getTypes'])->name('get_types')->middleware('auth:h_users');
Route::post('/ajax/brands', [ProductController::class, 'getBrands'])->name('get_brands')->middleware('auth:h_users');
Route::post('/ajax/colors', [ProductController::class, 'getColors'])->name('get_colors')->middleware('auth:h_users');
Route::post('/ajax/setClient', [BasketController::class, 'resetClientBasket'])->name('set_client')->middleware('auth:h_users');
Route::get('/ajax/products/{klantCode}', [UserController::class, 'getClientProducts'])->name('get_client_products')->where(['klantCode' => '[0-9]+'])->middleware('auth:h_users');
Route::get('/ajax/users/{klantCode}', [UserController::class, 'getClientUsers'])->name('get_client_users')->where(['klantCode' => '[0-9]+'])->middleware('auth:h_users');
Route::post('/ajax/generate-report', [ReportController::class, 'generateReport'])->name('generate_report')->middleware('auth:h_users');

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

Route::get('/after_authentication', function () {
    $privileges = json_decode(auth()->user()->privileges);
    if($privileges) {
        if(in_array('show_tiles', $privileges)) return redirect()->route('products_tiles');
        else return redirect()->route('products');
    } else {
        return redirect()->route('products');
    }
})->middleware('auth:h_users');
