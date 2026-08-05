<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rutas Web para la interfaz del Dashboard SOA.
|
*/

Route::get('/', function () {
    return view('dashboard');
});