<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\xmlController;
use App\Http\Controllers\pageController;
use App\Http\Controllers\AuthController;

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
    return view('templates.portal');
})->middleware('auth.basic');
Route::get('/parsexml', function () {
    return view('templates.development')->with(['data' => ['include_view' => 'development.xml']]);
})->name('parseXml_Index')->middleware('auth.basic');

// Route::get('/login', [authController::class, 'showLogin'])->name('login');
// Route::post('/login/attempt', [authController::class, 'attemptLogin'])->name('attempt_login');
Route::get('/account', [authController::class, 'showAccount'])->name('account')->middleware('auth:admin');
// Route::get('/login')->name('login');

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
