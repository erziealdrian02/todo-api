<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\CheclistController;
use App\Http\Controllers\Api\CheclistItemsController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware(Authenticate::using('sanctum'));

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(Authenticate::using('sanctum'));

// Todo
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checklist', [CheclistController::class, 'index']);
    Route::post('/checklist', [CheclistController::class, 'store']);
    Route::get('/checklist/{id}', [CheclistController::class, 'show']);
    Route::put('/checklist/{id}', [CheclistController::class, 'update']);
    Route::delete('/checklist/{id}', [CheclistController::class, 'destroy']);

    Route::get('/checklist/{checklist_id}/item', [CheclistItemsController::class, 'index']);
    Route::post('/checklist/{checklist_id}/item', [CheclistItemsController::class, 'store']);
    Route::get('/checklist/{checklist_id}/item/{item_id}', [CheclistItemsController::class, 'show']);
    Route::put('/checklist/{checklist_id}/item/{item_id}', [CheclistItemsController::class, 'update']);
    Route::put('/checklist/{checklist_id}/item/rename/{item_id}', [CheclistItemsController::class, 'rename']);
    Route::delete('/checklist/{checklist_id}/item/{item_id}', [CheclistItemsController::class, 'destroy']);
});

