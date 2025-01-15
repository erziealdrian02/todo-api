<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\TodoController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware(Authenticate::using('sanctum'));

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(Authenticate::using('sanctum'));

// Todo
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/todo', [TodoController::class, 'index']);
    Route::get('/todo/{id}', [TodoController::class, 'show']);
    Route::post('/todo', [TodoController::class, 'store']);
    Route::put('/todo/{id}', [TodoController::class, 'update']);
    Route::delete('/todo/{id}', [TodoController::class, 'destroy']);
});
