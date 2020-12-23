<?php

use App\Http\Controllers\LuckyWheelController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
})->name('home');

Route::get('spins/{id}', [LuckyWheelController::class, 'ShowWheel'])->name('spins.detail');
Route::get('spins', [LuckyWheelController::class, 'ShowAllWheel'])->name('spins');
