<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); });
Route::get('/dashboard', function () { return view('dashboard'); });
Route::get('/sensors', function () { return view('sensors'); });
Route::get('/logs', function () { return view('logs'); });
Route::get('/profile', function () { return view('profile'); });