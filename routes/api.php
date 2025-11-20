<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PartnersCodeController;
use App\Http\Controllers\Api\V1\ProfileController as V1ProfileController;
use App\Http\Controllers\Api\V1\PartnerCodesController as V1PartnerCodesController;


//no need to login
Route::group(['middleware' => ['frontend.secret'], 'prefix' => 'v1'], function () {
    Route::post('/login', [V1ProfileController::class, 'login']);
    Route::post('/forgot-password', [V1ProfileController::class, 'forgotPassword']);
    Route::post('/reset-password', [V1ProfileController::class, 'resetPassword']);
});

Route::group(['middleware' => ['auth:sanctum', 'frontend.secret'], 'prefix' => 'v1'], function () {
    Route::get('profile', [V1ProfileController::class, 'profile']);
    Route::post('/logout', [V1ProfileController::class, 'logout']);
    Route::put('/profile', [V1ProfileController::class, 'updateProfile']);      //update profile
    Route::put('/password', [V1ProfileController::class, 'updatePassword']);      //update profile

    Route::get('codes', [V1PartnerCodesController::class, 'allCode']);
    Route::get('codes/{code_id}/transactions', [V1PartnerCodesController::class, 'codeTransaction']);
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/partner/me', [AuthPartnerController::class, 'me']);
//     Route::post('/partner/logout', [AuthPartnerController::class, 'logout']);
// });


// Partners Code Routes
Route::get('/partner-codes', [PartnersCodeController::class, 'index']);
Route::get('/partner-codes/{id}', [PartnersCodeController::class, 'show']);
Route::post('/partner-codes', [PartnersCodeController::class, 'store']);
Route::put('/partner-codes/{id}', [PartnersCodeController::class, 'update']);
Route::delete('/partner-codes/{id}', [PartnersCodeController::class, 'destroy']);
