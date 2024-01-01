<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

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

Route::post('charge', [TransactionController::class, 'charge'])->name('charge');


Route::name('stripe.')
    ->controller(TransactionController::class)
    ->prefix('stripe')
    ->group(function () {
        Route::get('payment', 'index')->name('index');
        Route::post('payment', 'store')->name('store');
    });
