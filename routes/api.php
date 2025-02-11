<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::group(['prefix' => 'auth'], function () {
    Route::get('test', [AuthenticationController::class, 'index']);
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::post('refresh', [AuthenticationController::class, 'refresh']);
});

Route::middleware([JwtMiddleware::class])->group(function () {
    // Route for displaying a list of books
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // Route for showing a form to create a new book
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');

    // Route for storing a newly created book
    Route::post('/books', [BookController::class, 'store'])->name('books.store');

    // Route for displaying a specific book
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    // Route for showing a form to edit a specific book
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');

    // Route for updating a specific book
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');

    // Route for deleting a specific book
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});
