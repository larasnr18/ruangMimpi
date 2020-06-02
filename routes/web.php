<?php

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
    return view('index');
});

Route::get('pesan-tiket', 'PesanTiketController@index');
Route::post('/tambah','PesanTiketController@tambah');
Route::get('/cek-tiket','PesanTiketController@cek');
Route::get('unggah-pembayaran','UnggahPembayaranController@index');
Route::get('/unggah','UnggahPembayaranController@unggahBuktiPembayaran');
Route::post('/testing','UnggahPembayaranController@testing');
Route::get('admin-home','VerifikasiPembayaranController@index'); //ini
Route::get('/admin-home/gantiStatus/{kode}','VerifikasiPembayaranController@gantiStatus'); //ini
Route::get('ticketing','TicketingController@index'); //ini
Route::post('/perbarui','TicketingController@update'); //ini
Auth::routes([
    
]);

Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/ticketing','HomeController@ticketing')->name('ticketing');
