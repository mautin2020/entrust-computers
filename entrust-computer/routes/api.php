<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// public routes
Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');
Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
Route::put('verify/phone', 'App\Http\Controllers\Auth\RegisterController@verifyPhone');
Route::post('verify/resend', 'App\Http\Controllers\Auth\RegisterController@resendCode');
Route::post('resetPassword/resend', 'App\Http\Controllers\User\SettingsController@sendResetPasswordCode');
Route::put('reset/password', 'App\Http\Controllers\User\SettingsController@resetPassword');


Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout');

    Route::put('settings/profile', 'App\Http\Controllers\User\SettingsController@updateProfile');
    Route::put('settings/password', 'App\Http\Controllers\User\SettingsController@updatePassword');
    Route::post('settings/addProfileImage', 'App\Http\Controllers\User\SettingsController@addProfilePhoto');
    Route::get('getMe', 'App\Http\Controllers\User\MeController@getMe');
    
    // product
    Route::get('product/getAllProduct', 'App\Http\Controllers\Product\ProductController@index');
    Route::get('product/getByID/{id}', 'App\Http\Controllers\Product\ProductController@getProductById');
    Route::post('product/create', 'App\Http\Controllers\Product\ProductController@createProduct');
    Route::put('product/update/{id}', 'App\Http\Controllers\Product\ProductController@updateProduct');
    Route::delete('product/delete/{id}', 'App\Http\Controllers\Product\ProductController@destroy');

    // general product ledger
    Route::get('generalProduct/getLedger', 'App\Http\Controllers\Product\GeneralProductLedgerController@getGeneralProductLedger');
    Route::get('generalProduct/productBalance/{productName}', 'App\Http\Controllers\Product\GeneralProductLedgerController@productBalance');
    //general product closing stock
    Route::post('generalProduct/closingStock', 'App\Http\Controllers\Product\GeneralProductLedgerController@closingStock');

    
    // laptop product
    Route::post('laptopProduct/create', 'App\Http\Controllers\Product\LaptopProductController@addLaptopProduct');
    Route::get('laptopProduct/getAll', 'App\Http\Controllers\Product\LaptopProductController@index');
    
    Route::get('laptopProduct/getBalance/{productCode}', 'App\Http\Controllers\Product\ProductLedgerController@productBalance');
    Route::get('laptopProduct/getByID/{id}', 'App\Http\Controllers\Product\LaptopProductController@getLaptopProductById');
    
    // laptop product ledger
    Route::get('laptopProduct/getLedger', 'App\Http\Controllers\Product\ProductLedgerController@getProductLedger');
    // closing stock
    Route::post('laptop/closingStock', 'App\Http\Controllers\Product\ProductLedgerController@closingStock');
    
    Route::put('laptopProduct/update/{id}', 'App\Http\Controllers\Product\LaptopProductController@updateLaptopProduct');
    Route::delete('laptopProduct/delete/{id}', 'App\Http\Controllers\Product\LaptopProductController@destroy');
});

// Route::middleware('auth:sanctum')->get('/user', function () {

    
// });
