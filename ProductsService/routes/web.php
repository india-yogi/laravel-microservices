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

Route::prefix('v1/products')->controller(OrderController::class)->middleware([])->group(function () {
    Route::get('/products', 'index');
    Route::post('/products', 'store');
    Route::get('/products/{id}', 'show');
    Route::put('/products/{id}', 'update');    
    Route::patch('/products/{id}', 'update');
    Route::delete('/products/{id}', 'destroy');
});