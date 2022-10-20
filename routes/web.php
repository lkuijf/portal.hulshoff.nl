<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\xmlController;

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
    return view('welcome_hulshoff');
});
Route::get('/front', function () {
    return view('templates.portal');
});
Route::get('/parsexml', function () {
    return view('templates.parseXml_index');
});
Route::get('/parsexml/producten', [xmlController::class, 'importXml'])->defaults('type', 'producten')->name('parseXmlProducten');
Route::get('/parsexml/klanten', [xmlController::class, 'importXml'])->defaults('type', 'klanten')->name('parseXmlKlanten');
Route::get('/parsexml/voorraden', [xmlController::class, 'importXml'])->defaults('type', 'voorraden')->name('parseXmlVoorraden');
Route::get('/parsexml/wmsorders', [xmlController::class, 'importXml'])->defaults('type', 'wmsorders')->name('parseXmlWmsorders');
