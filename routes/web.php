<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [DataController::class, 'index']);
Route::post('/add', [DataController::class, 'store']);
Route::post('/update', [DataController::class, 'update']);
Route::post('/delete', [DataController::class, 'destroy']);
Route::post('/view', [DataController::class, 'view']);