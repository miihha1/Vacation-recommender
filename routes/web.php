<?php

use App\Http\Controllers\CompareController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SearchController::class, 'index'])->name('search.index');
Route::post('/', [SearchController::class, 'search'])->name('search.submit');
Route::get('/destination.php', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/compare.php', [CompareController::class, 'index'])->name('compare.index');
Route::get('/stats.php', [StatsController::class, 'index'])->name('stats.index');

Route::get('/dovolenka/public/destination.php', [DestinationController::class, 'show']);
Route::get('/dovolenka/public/compare.php', [CompareController::class, 'index']);
Route::get('/dovolenka/public/stats.php', [StatsController::class, 'index']);
