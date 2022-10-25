<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\xmlController;
use App\Http\Controllers\pageController;

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
    return view('welcome_hulshoff')->with(['data' => ['content' => '
        <h1>Welkom</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tortor dignissim convallis aenean et tortor. Ullamcorper a lacus vestibulum sed arcu. Accumsan sit amet nulla facilisi morbi tempus iaculis urna id. Pellentesque pulvinar pellentesque habitant morbi tristique senectus et netus et.</p>
        <ul>
            <li><a href="/front">Front-end elementen</a></li>
            <li><a href="/parsexml">Verwerk XML</a></li>
        </ul>
    ']]);
});
Route::get('/front', function () {
    return view('templates.portal');
});
Route::get('/parsexml', function () {
    return view('templates.parseXml_index');
});
// Route::get('/parsexml', [pageController::class, 'pageXml']);

Route::get('/parsexml/producten', [xmlController::class, 'importXml'])->defaults('type', 'producten')->name('parseXmlProducten');
Route::get('/parsexml/klanten', [xmlController::class, 'importXml'])->defaults('type', 'klanten')->name('parseXmlKlanten');
Route::get('/parsexml/voorraden', [xmlController::class, 'importXml'])->defaults('type', 'voorraden')->name('parseXmlVoorraden');
Route::get('/parsexml/wmsorders', [xmlController::class, 'importXml'])->defaults('type', 'wmsorders')->name('parseXmlWmsorders');

Route::post('/post/xml/klantUit', [xmlController::class, 'savePostedXml'])->defaults('xmltype', 'klant');
Route::post('/post/xml/artikelUit', [xmlController::class, 'savePostedXml'])->defaults('xmltype', 'artikel');
Route::post('/post/xml/orderUit', [xmlController::class, 'savePostedXml'])->defaults('xmltype', 'order');
Route::post('/post/xml/vrdstandUit', [xmlController::class, 'savePostedXml'])->defaults('xmltype', 'vrdstand');

//TEMP GET-TEST
// Route::get('/post/xml/klantUit', function () {
//     return 'doet het';
// });
