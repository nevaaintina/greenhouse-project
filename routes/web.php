<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); });
Route::get('/dashboard', function () { return view('dashboard'); });
Route::get('/sensors', function () { return view('sensors'); });
Route::get('/logs', function () { return view('logs'); });
Route::get('/profile', function () { return view('profile'); });
Route::get('/grafik', function () { return view('grafik'); })->name('grafik');
Route::get('/settings', function () { return view('settings'); })->name('settings');