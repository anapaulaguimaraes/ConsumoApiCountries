<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountriesController;

Route::get('/', [CountriesController::class, 'index']);
Route::get('/save-countries', [CountriesController::class, 'fetchAndSaveCountries']);