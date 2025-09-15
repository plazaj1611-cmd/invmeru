<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\postcontroller;

Route::get('/', [HomeController::class, 'index']);

Route::get('/posts', [postcontroller::class, 'index']);
Route::get('/posts/formun', [postcontroller::class, 'formun']);
Route::get('/posts/{post}/{category}', [postcontroller::class, 'show']);
