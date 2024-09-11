<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CancellOrder;
use App\Models\Cabinet;
use App\Models\Client;
use App\Models\Reservation;
use App\Services\PayKeeperService;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Place;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class  OrderController extends Controller
{
    public function order(Request $request)
    {

        $request -> validate([
            'client.name' =>'required',
            'client.phone' => 'required',
            'reservation' => [
            function ($attribute, $value, $fail) use ($request) {
                $reserved = false;
                $reservation_data = $request->get('reservation');
                if($reservation_data['endTime'] === '24:00') {
                    $reservation_data['endTime'] = '23:59';
                }
                foreach ($request->get('reservations') as $reservation) {
                    if($reservation['type'] === 'cabinet') {
                        $cabinet = Cabinet::findOrFail($reservation['id']);
                        $reserved = $cabinet->reservations()
                            ->whereDate('date', '=', $reservation_data['selectedDay'])
                            ->whereTime('end', '>=', \Carbon\Carbon::parse($reservation_data['selectedDay'] . ' ' .$reservation_data['startTime'] ))
                            -> whereTime('start' , '<=', \Carbon\Carbon::parse($reservation_data['selectedDay'] . ' ' . $reservation_data['endTime']))
                            ->whereHas('order', function ($query) {
                                $query->where('status','success')
                                ->orWhere('status', 'progress');
                            }) -> exists();
                    } else if($reservation['type'] === 'place') {
                        $place = Place::findOrFail($reservation['id']);

                        $reserved = $place->reservations()
                            ->whereDate('date', '=', $reservation_data['selectedDay'])
                            ->whereTime('end', '>=', \Carbon\Carbon::parse($reservation_data['selectedDay'] . ' ' .$reservation_data['startTime'] ))
                            -> whereTime('start' , '<=', \Carbon\Carbon::parse($reservation_data['selectedDay'] . ' ' . $reservation_data['endTime']))
                            ->whereHas('order', function ($query) {
                                $query->where('status','success')
                                    ->orWhere('status', 'progress');
                            }) -> exists();
                    }

                    if($reserved) {
                        break;
                    }
                }
                if($reserved) {
                    $fail('Места в вашем заказе уже забронированы, выберите места заново');
                }
        }],
        ], [
            'client.name.required' => 'Введите ваше имя',
            'client.phone.required' => 'Введите ваш телефон',
        ]);
        $client = Client::updateOrCreate([
            'phone' =>$request->get('client')['phone']
        ], [
            'name' => $request->get('client')['name']
        ]);
        if($request->get('reservation')['endTime'] === '24:00') {
            $endDate = $request->get('reservation')['selectedDay'] . ' ' . '23:59';
        } else {
            $endDate = $request->get('reservation')['selectedDay'] . ' ' . $request->get('reservation')['endTime'];
        }

        $order = Order::create([
            'status' => 'progress',
            'client_id' => $client -> id,
            'price' => $request->get('reservation')['price'],
            'date' =>$request->get('reservation')['selectedDay'],
            'start' => $request->get('reservation')['selectedDay'] . ' ' . $request->get('reservation')['startTime'],
            'end' => $endDate,
        ]);




        $reservation_data = [
            'order_id' => $order -> id,
            'start' => $request->get('reservation')['selectedDay'] . ' ' . $request->get('reservation')['startTime'],
            'end' => $endDate,
            'date' => $request->get('reservation')['selectedDay'],
        ];
        foreach ($request->get('reservations') as $reservation_request) {

            if($reservation_request['type'] === 'place') {
                $place = Place::findOrFail($reservation_request['id']);
                $reservation = $place ->reservations() -> create($reservation_data);
            }
            if($reservation_request['type'] === 'cabinet') {
                $cabinet = Cabinet::findOrFail($reservation_request['id']);
                $reservation = $cabinet ->reservations() -> create($reservation_data);
            }
        }
        $data = array(
            'token' => urldecode(env('ALFA_TOKEN')),
            //'userName' => env('ALFA_USERNAME'),
            //'password' => env('ALFA_PASSWORD'),
            'orderNumber' => $order->id,
            'amount' => $request->get('reservation')['price'] * 100,
            'sessionTimeoutSecs'=> 360,
            'returnUrl' => env('ALFA_RETURN_URL')
        );
        $alfa_pay = new PaymentService();
        $response = $alfa_pay -> gateway('register.do', $data);

        $order -> alfa_order_id = $response['orderId'];
        $order -> save();

       CancellOrder::dispatch($order)
            ->delay(now()->addMinutes(1));
        return $response;
    }
    public function getOrderInf(Request $request) {
        $data = array(
           // 'userName' => env('ALFA_USERNAME'),
            //'password' => env('ALFA_PASSWORD'),
            'token' => urldecode(env('ALFA_TOKEN')),
            'orderId' => $request->get('order_id')
        );
        $alfa_pay = new PaymentService();
        $response = $alfa_pay -> gateway('getOrderStatus.do', $data);
        return $response;
    }

    public function orderTest(Request $request)
    {
        dd($request->all());
    }

}

