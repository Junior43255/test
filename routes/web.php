<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kpi;

Route::get('/', function () {
    return view('home');
});

Route::get('/question1', [Kpi::class, 'index']);


Route::get('/question2', [Kpi::class, 'normal_distribution']);
