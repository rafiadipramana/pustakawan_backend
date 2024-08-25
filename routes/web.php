<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

// Display the welcome view
Route::get('/', function () {
    return view('welcome');
});
