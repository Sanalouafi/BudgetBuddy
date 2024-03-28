<?php

use App\Http\Controllers\Auth\AuthController as AuthController;
use App\Http\Controllers\User\ExpenseController as ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware(['auth:sanctum'])->group(function () {
    // Route::get('/expenses', [ExpenseController::class, 'index']);
    // Route::post('/expenses', [ExpenseController::class, 'store']);
    // Route::get('/expenses/{expense}', [ExpenseController::class, 'show']);
    // Route::put('/expenses/{expense}', [ExpenseController::class, 'update']);
    // Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy']);
    Route::apiResource('/expenses', ExpenseController::class);
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
Route::post('/register', [AuthController::class, 'register']);
