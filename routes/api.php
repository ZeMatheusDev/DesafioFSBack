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

Route::post('/account/store', 'AccountController@store')->name('store');

Route::post('/login', 'LoginController@login')->name('login');

Route::get('/clinic/getAllClinics', 'ClinicaController@getAllClinics')->name('clinics.list');
Route::post('/clinic/store', 'ClinicaController@store')->name('clinic.store');
Route::post('/clinic/delete', 'ClinicaController@delete')->name('clinic.delete');
Route::get('/clinic/edit/{id}', 'ClinicaController@edit')->name('clinic.edit');
Route::post('/clinic/update/{id}', 'ClinicaController@update')->name('clinic.update');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
