<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin api routes for your application.
|
*/
use App\Http\Controllers\Admin\Api\OrderController;
use App\Http\Controllers\Admin\Api\PlacesController;
use App\Http\Controllers\Admin\Api\ReservationsController;
use App\Http\Controllers\Admin\Api\StatisticController;
use App\Http\Controllers\Admin\Api\SettingController;
use App\Http\Controllers\Admin\Api\EventDateController;
Route::group(['middleware' => 'auth'], function () {
    Route::get('/orders', [OrderController::class, 'index']) -> name('orders.list');
    Route::get('/orders/manual', [OrderController::class, 'checkManual']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::get('/order/{id}/manual', [OrderController::class, 'showManual']);
    Route::get('/order/{id}/status', [OrderController::class,'getOrderPaymentStatus'])
        -> name('order.status');
    Route::get('/places/navigation', [PlacesController::class, 'navigation'])
        -> name('places.navigation');
    Route::post('/test-email', [ReservationsController::class, 'testEmail']);

    Route::get('/reservations', [ReservationsController::class, 'index']) -> name('reservations');

    Route::get('/reservation/other' , [ReservationsController::class, 'getOtherReservations'])
        -> name('reservation.other');
    Route::post('/reservation/cancel/{id}', [ReservationsController::class, 'cancelReservation'])
        ->name('reservation.cancel');
    Route::post('/reservation/cancel-order/{id}', [ReservationsController::class, 'cancelOrder'])
        ->name('reservation.cancel-order');

    Route::post('/reservation/order', [ReservationsController::class, 'order'])
        ->name('reservation.order');
    Route::get('/statistic', [StatisticController::class, 'index'])->name('statistic');

    Route::post('/setting/set-start-time', [SettingController::class, 'setStartTime'])
        ->name('setting.set-start-time');
    Route::get('/settings/get-start-time', [SettingController::class, 'getStartTime'])
        ->name('setting.get-start-time');
    Route::apiResource('/event-dates',EventDateController::class);
});




















