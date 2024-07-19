<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use App\Models\Cabinet;
use App\Models\Client;
use App\Models\Order;
use App\Models\Place;
use App\Models\Reservation;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReservationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $date = json_decode($request-> get('date'));
        $date = $date -> date;
        $places = Place::whereHas('reservations', function($query) use ($date) {
            $query->where('date', $date);
            $query->whereHas('order', function($query){
                $query->where('status', 'success');
            });
        }) -> with(['reservations' => function($query) use ($date){
            $query->where('date', $date);
            $query->whereHas('order', function($query){
                $query->where('status', 'success');
            });
            $query->with('order.client');
        }]) ->  orderBy('number') -> get();
        $cabinets = Cabinet::whereHas('reservations', function($query) use ($date){
            $query->where('date', $date);
            $query->whereHas('order', function($query){
                $query -> where('status', 'success');
            } );

        }) -> with(['reservations' => function($query) use ($date){
            $query->where('date', $date);
            $query->whereHas('order', function($query){
                $query->where('status', 'success');
            });
            $query->with('order.client');
        }]) ->  orderBy('number') -> get();
        return ['places' => $places, 'cabinets' => $cabinets];
    }

    public function getOtherReservations(Request $request)
    {
        $order = Order::with('reservations.reservationable')->findOrFail($request->get('id'));
        return $order;
    }

    public function order(Request $request) {
        $request -> validate([
            'client.name' =>'required',
            'client.phone' => 'required',
            'client.type' => 'required',
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
            'client.name.required' => 'Введите имя клиента',
            'client.phone.required' => 'Введите телефон клиента',
            'client.type.required' => 'Введите тип бронирования',
        ]);
        $client = Client::create([
            'phone' => $request->get('client')['phone'],
            'name' => $request->get('client')['name']
        ]);
        if($request->get('reservation')['endTime'] === '24:00') {
            $endDate = $request->get('reservation')['selectedDay'] . ' ' . '23:59';
        } else {
            $endDate = $request->get('reservation')['selectedDay'] . ' ' . $request->get('reservation')['endTime'];
        }
        $order = Order::create([
            'status' => 'success',
            'client_id' => $client -> id,
            'type' => $request->get('client')['type'],
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
        $order -> save();
        //Mail::to('marat@pandaworks.ru')->send(new OrderMail($order));
        return 'success';
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation ->delete();
        return $id;
    }

    public function cancelOrder($id) {
        $order = Order::findOrFail($id);
        $order -> status = 'cancelled';
        $order->save();
        return $id;
    }

    public function testEmail()
    {
        $order = ['test'=>'test'];
        Mail::to('gwynbleid11@yandex.ru')->send(new OrderMail($order));
    }
}
