<?php

use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans/webhook', [MidtransController::class, 'webhook'])
    ->name('midtrans.webhook');
