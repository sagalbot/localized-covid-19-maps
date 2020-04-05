<?php

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

Route::redirect('/', '/timeline');

Route::get('/timeline', 'TimelineController')->name('timeline');

Route::get('/suppression', 'SuppressionController')->name('suppression');

Route::get('/regions', function () {
    return \Inertia\Inertia::render('Regions');
})->name('regions');
