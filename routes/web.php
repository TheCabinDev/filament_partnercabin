<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\SendReservationSuccessEmail;
use App\Models\Partners;
use App\Models\PartnersCode;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/test-queue', function(){
    try {
        $partner = Partners::first();

        if (!$partner) {
            return 'No partner found! Please add a partner first.';
        }

        $partnerCode = PartnersCode::where('id_partner', $partner->id)->first();

        if (!$partnerCode) {
            return 'No partner code found!';
        }

        SendReservationSuccessEmail::dispatch(
            $partner,
            $partnerCode,
            'RES-QUEUE-' . time(),
            500000,
            50,
            now()->format('Y-m-d H:i:s')
        );

        return 'Email job dispatched to: ' . $partner->email . '<br>Unique Code: ' . $partnerCode->unique_code . '<br>Run: php artisan queue:work';

    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>Line: ' . $e->getLine();
    }
});
