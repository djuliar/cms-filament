<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/page', [HomeController::class, 'page'])->name('page');
