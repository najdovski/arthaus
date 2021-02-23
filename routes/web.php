<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

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
});

Auth::routes(['register' => config('app.registrations_allowed')]);

Route::prefix('activities')->middleware('auth')->group(function() {
    Route::get('/', [ActivityController::class, 'index'])->name('activities');
    //hasAccessToken middleware
});
