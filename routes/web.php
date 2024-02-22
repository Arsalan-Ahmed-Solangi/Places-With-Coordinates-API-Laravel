<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MapController;
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

Route::get('/', [UserController::class, 'index'])->name('home');

Route::post('/getCities', [UserController::class, 'getCities'])->name('getCities');
Route::get('/censusmap',[MapController::class,'index'])->name('censusmap');
Route::post('/downloadCSV', [UserController::class, 'downloadCSV'])->name('downloadCSV');
Route::post('/getCityStores',[MapController::class,'getCityStores'])->name('getCityStores');
Route::post('/getCityDetails',[MapController::class,'getCityDetails'])->name('getCityDetails');
