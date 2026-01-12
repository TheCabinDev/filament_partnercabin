<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PartnersCodeController;
use App\Http\Controllers\Api\V1\ProfileController as V1ProfileController;
use App\Http\Controllers\Api\V1\PartnerCodesController as V1PartnerCodesController;
use App\Http\Controllers\Api\V1\WithdrawMoneyController as V1WithdrawMoneyController;
use App\Http\Controllers\Api\V1\Reservation\ReservationCodeController as V1RESVCodeController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PushSubscriptionController;
use App\Http\Controllers\PartnerCodeController;


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
	Route::patch('codes/{code_id}/fees', [V1PartnerCodesController::class, 'updateFees']);

    Route::get('money-balance', [V1WithdrawMoneyController::class, 'moneyBalance']);
    Route::post('/withdraw', [V1WithdrawMoneyController::class, 'withdraw']);
    Route::get('withdraw-history', [V1WithdrawMoneyController::class, 'withdrawHistory']);
});

Route::group(['middleware' => ['coreresv.secret'], 'prefix' => 'v1'], function () {
    Route::post('core-reservation/detail', [V1RESVCodeController::class, 'codeDetail']);
    Route::post('core-reservation/usecode', [V1RESVCodeController::class, 'useCodeAfterFinalStatus']);

});


Route::get('/v1/push-subscriptions/vapid-key', [PushSubscriptionController::class, 'getVapidPublicKey']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Push Subscription
    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store']);
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete']);
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
