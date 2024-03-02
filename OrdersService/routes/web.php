<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('v1/orders')->controller(OrderController::class)->middleware([])->group(function () {
    Route::get('/orders', 'index');
    Route::post('/orders', 'store');
    Route::get('/orders/{id}', 'show');
    Route::put('/orders/{id}', 'update');    
    Route::patch('/orders/{id}', 'update');
    Route::delete('/orders/{id}', 'destroy');
});