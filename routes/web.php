<?php

use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/applications', [JobApplicationController::class, 'index'])
    ->name('applications.index');

Route::get('/applications/create', [JobApplicationController::class, 'create'])
    ->name('applications.create');

Route::post('/applications', [JobApplicationController::class, 'store'])
    ->name('applications.store');

Route::get('/applications/{jobApplication}/edit', [JobApplicationController::class, 'edit'])
    ->name('applications.edit');

Route::put('/applications/{jobApplication}', [JobApplicationController::class, 'update'])
    ->name('applications.update');

Route::delete('/applications/{jobApplication}', [JobApplicationController::class, 'destroy'])
    ->name('applications.destroy');
