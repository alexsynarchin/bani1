<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Places
use App\Http\Controllers\Api\PlaceController;
Route::get('/places/list/{floor}',[PlaceController::class, 'index']) -> name('places.list');
//Cabinets
use App\Http\Controllers\Api\CabinetController;
Route::get('/cabinets/list/{floor}',[CabinetController::class, 'index']) -> name('cabinets.list');
//Order
use App\Http\Controllers\Api\OrderController;
Route::post('/reservation-order', [OrderController::class, 'order']) -> name('order-submit');

Route::post('/reservation-order-inf', [OrderController::class, 'getOrderInf']) -> name('order-inf');
Route::post('/order-test', [OrderController::class,'orderTest']);

use App\Http\Controllers\Api\PaymentController;

Route::post('/payment-result', [PaymentController::class, 'paymentResult']) -> name('payment.result');

use App\Http\Controllers\Api\BookingController;
Route::get('/get-current-date', [BookingController::class, 'getCurrentDate'])->name('booking.current-date');

use App\Http\Controllers\Api\EventDateController;
Route::get('/event-dates', [EventDateController::class, 'index'])->name('event-dates');

use App\Http\Controllers\Api\SettingController;
Route::get('/settings/get-start-time', [SettingController::class, 'getStartTime'])
    -> name('get-start-time');
use App\Http\Controllers\Api\ReservationController;
Route::post('/reservation/check-contact', [ReservationController::class, 'checkContactData']);
Route::get('/reservations', [ReservationController::class, 'index']) -> name('reservations');
Route::get('/reservations/navigation', [ReservationController::class, 'navigation']) -> name('reservations.navigation');


