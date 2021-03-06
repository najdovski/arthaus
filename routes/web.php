<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\EmailShareController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => config('app.registrations_allowed')]);

Route::prefix('activities')->middleware('auth')->group(function() {
    Route::get('/', [ActivityController::class, 'index'])->name('activities');
    Route::get('/show/{id}', [ActivityController::class, 'show'])->name('show-activity');
    Route::get('/create', [ActivityController::class, 'create'])->name('create-activity');
    Route::post('/store', [ActivityController::class, 'store'])->name('store-activity');
    Route::get('/edit/{id}', [ActivityController::class, 'edit'])->name('edit-activity');
    Route::put('/update', [ActivityController::class, 'update'])->name('update-activity');
    Route::delete('/{id}', [ActivityController::class, 'destroy'])->name('remove-activity');
});

Route::post('/email-share', [EmailShareController::class, 'index'])->middleware('auth')->name('email-share');

Route::get('/reports', [ReportController::class, 'index'])->middleware('auth')->name('reports');
