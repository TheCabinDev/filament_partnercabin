<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthPartnerController;
use App\Http\Controllers\Api\PartnersCodeController;

Route::post('/partner/login', [AuthPartnerController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/partner/me', [AuthPartnerController::class, 'me']);
    Route::post('/partner/logout', [AuthPartnerController::class, 'logout']);
});


// Partners Code Routes
Route::get('/partner-codes', [PartnersCodeController::class, 'index']);
Route::get('/partner-codes/{id}', [PartnersCodeController::class, 'show']);
Route::post('/partner-codes', [PartnersCodeController::class, 'store']);
Route::put('/partner-codes/{id}', [PartnersCodeController::class, 'update']);
Route::delete('/partner-codes/{id}', [PartnersCodeController::class, 'destroy']);
