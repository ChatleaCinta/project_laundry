<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', 'PetugasController@register');
Route::post('login', 'PetugasController@login');
Route::get('/', function(){
    return Auth::user()->level;
})->middleware('jwt.verify');

Route::get('user', 'PetugasController@getAuthenticatedUser')->middleware('jwt.verify');

//pelanggan
Route::get('ip/{id}','PelangganController@index')->middleware('jwt.verify');
Route::post('tp','PelangganController@store')->middleware('jwt.verify');
Route::put('/upp/{id}','PelangganController@update')->middleware('jwt.verify');
Route::delete('dp/{id}','PelangganController@destroy')->middleware('jwt.verify');
Route::get('sp','PelangganController@tampil')->middleware('jwt.verify');

//jenis_cuci
Route::get('ijc/{id}','JenisCuciController@index')->middleware('jwt.verify');
Route::post('tjc','JenisCuciController@store')->middleware('jwt.verify');
Route::put('/ujc/{id}','JenisCuciController@update')->middleware('jwt.verify');
Route::delete('djc/{id}','JenisCuciController@destroy')->middleware('jwt.verify');
Route::get('sjc','JenisCuciController@tampil')->middleware('jwt.verify');

//transaksi
Route::get('it/{tgl_transaksi}/{tgl_selesai}','TransaksiController@index')->middleware('jwt.verify');
Route::post('tambaht','TransaksiController@store')->middleware('jwt.verify');
Route::put('/ut/{id}','TransaksiController@update')->middleware('jwt.verify');
Route::delete('dt/{id}','TransaksiController@destroy')->middleware('jwt.verify');

//detail_transaksi
Route::get('idt/{id}','DetailTransaksiController@index')->middleware('jwt.verify');
Route::post('tdt','DetailTransaksiController@store')->middleware('jwt.verify');
Route::put('/udt/{id}','DetailTransaksiController@update')->middleware('jwt.verify');
Route::delete('ddt/{id}','DetailTransaksiController@destroy')->middleware('jwt.verify');
Route::get('sdt','DetailTransaksiController@tampil')->middleware('jwt.verify');

