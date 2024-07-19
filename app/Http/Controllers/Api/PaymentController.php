<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use App\Models\Order;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function paymentResult(Request $request)
    {
        $data = array(
            'token' => urldecode(env('ALFA_TOKEN')),
           // 'userName' => env('ALFA_USERNAME'),
            //'password' => env('ALFA_PASSWORD'),
            'orderId' => $request->get('order_id')
        );
        $text = '';
        $order = Order::where('alfa_order_id', $request->get('order_id')) -> firstOrFail();
        $alfa_pay = new PaymentService();
        $response = $alfa_pay->gateway('getOrderStatus.do', $data);
        if($response['OrderStatus'] === 2) {
            $order -> status = 'success';
            $text = 'Заказ №' . $order->id. ' забронирован.';

        } elseif ($response['OrderStatus'] === 0 || $response['OrderStatus'] === 5) {
            $order -> status = 'progress';
            $text = 'Платеж на данный момент не обработан банком, при необходимости свяжитесь с нами.';
        } else {
            $text = $response['ErrorMessage'];
            $order -> status = 'cancelled';
        }
        $order -> save();

        return $text;
    }
}
