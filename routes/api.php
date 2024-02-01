<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\AuthController;

// Route::apiResource('/categories', App\Http\Controllers\Api\CategoryController::class);
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store'])->middleware(['auth:sanctum']);
Route::patch('/categories/{category}', [CategoryController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::get('/categories/{id}/books', [CategoryController::class, 'filter']);
// Route::apiResource('/books', App\Http\Controllers\Api\BooksController::class);
Route::get('/books', [BooksController::class, 'index']);
Route::post('/books', [BooksController::class, 'store'])->middleware(['auth:sanctum']);
Route::patch('/books/{books}', [BooksController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/books/{books}', [BooksController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::get('/books?', [BooksController::class, 'filter']);

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
