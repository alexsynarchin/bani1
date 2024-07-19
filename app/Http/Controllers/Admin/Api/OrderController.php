<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cabinet;
use App\Models\Order;
use App\Models\Place;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    const ITEM_PER_PAGE = 15;
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $orders = DB::table('orders')

         ->whereDate('created_at', '>', Carbon::parse('02.10.2023') -> toDateString())
            ->select('id', 'created_at', 'price', 'status', 'client_id', 'date', 'start', 'end')->orderBy('created_at','DESC')
            -> get();




   /*     foreach ($orders as $order) {
            if($order->reservations()->exists()) {
                $order -> date = Carbon::parse($order -> reservations[0]['date']) -> format('d-m-y');
                $order -> start = Carbon::parse($order -> reservations[0]['start']) -> format('H:i');
                $order -> end = Carbon::parse($order -> reservations[0]['end']) -> format('H:i');
            }

        }*/

        return   $orders;
    }

    public function show($id)
    {
            $order = Order::with('client')->with('reservations.reservationable')->findOrFail($id);
            return $order;

    }
    public function getOrderPaymentStatus($id)
    {
        $order = Order::findOrFAil($id);
        if($order->alfa_order_id === null) {
            $order->status = 'cancelled';
        } else {
            $data = array(
                'userName' => env('ALFA_USERNAME'),
                'password' => env('ALFA_PASSWORD'),
                'orderId' => $order->alfa_order_id,
            );
            $text = '';
            $alfa_pay = new PaymentService();
            $response = $alfa_pay -> gateway('getOrderStatus.do', $data);
            if($response['ErrorCode'] != 0) {
                $text = $response['ErrorMessage'];
                $order -> status = 'cancelled';
            }  else {
                if($response['OrderStatus'] === 2) {
                    $order -> status = 'success';
                } else if ($response['OrderStatus'] === 0) {
                    $order -> status = 'progress';
                } else {
                    $order -> status = 'cancelled';
                }

            }
        }

        $order -> save();
        return $order->status;
    }




    public function checkManual()
    {
        $orders =  Order::whereIn('type', ['non-cash', 'offsetting', 'transfer', 'sert', 'founder', 'invitation'])
            ->whereHas('reservations')
            ->whereDate('date', '>', Carbon::parse('23.11.2023') -> toDateString())
            ->where('status','cancelled')
            ->select('id', 'created_at', 'price', 'status', 'client_id', 'date', 'start', 'end')
            ->orderBy('created_at','DESC')
            -> get();
            return $orders;

    }


    public function showManual($id) {
        $order = Order::with('client')->with('reservations.reservationable')->findOrFail($id);
        $conflict_ids = [];
        foreach ($order->reservations as $reservation) {
            if($reservation -> reservationable_type === 'App\Models\Cabinet') {
                $cabinet = Cabinet::findOrFail($reservation -> reservationable_id);
                $reserved = $cabinet->reservations()
                    ->whereDate('date', '=', $order->date)
                    ->whereTime('end', '>=', \Carbon\Carbon::parse($order->start))
                    -> whereTime('start' , '<=', \Carbon\Carbon::parse($order->end))
                    ->whereHas('order', function ($query) use ($order) {
                        $query->where('id', '!=', $order->id)
                            ->where('status','success')
                            ->orWhere('status', 'progress');
                    }) -> exists();
                if($reserved) {
                   $conflicts =  $cabinet->reservations()
                        ->whereDate('date', '=', $order->date)
                        ->whereTime('end', '>=', \Carbon\Carbon::parse($order->start))
                        -> whereTime('start' , '<=', \Carbon\Carbon::parse($order->end))
                        ->whereHas('order', function ($query) use ($order) {
                            $query->where('id', '!=', $order->id)
                                ->where('status','success')
                                ->orWhere('status', 'progress');
                        }) -> pluck('order_id');
                    foreach ($conflicts as $conflict) {
                        $conflict_ids[] = $conflict;
                    }
                }
            } else if($reservation -> reservationable_type === 'App\Models\Place') {
                $place = Place::findOrFail($reservation -> reservationable_id);

                $reserved = $place->reservations()
                    ->whereDate('date', '=', $order->date)
                    ->whereTime('end', '>=', \Carbon\Carbon::parse($order->start ))
                    -> whereTime('start' , '<=', \Carbon\Carbon::parse($order->end))
                    ->whereHas('order', function ($query) use ($order) {
                        $query->where('id', '!=', $order->id)
                            ->where('status','success')
                            ->orWhere('status', 'progress');
                    }) -> exists();
                if($reserved) {
                    $conflicts =  $place->reservations()
                        ->whereDate('date', '=', $order->date)
                        ->whereTime('end', '>=', \Carbon\Carbon::parse($order->start))
                        -> whereTime('start' , '<=', \Carbon\Carbon::parse($order->end))
                        ->whereHas('order', function ($query) use ($order) {
                            $query->where('id', '!=', $order->id)
                                ->where('status','success')
                                ->orWhere('status', 'progress');
                        }) -> pluck('order_id');
                    foreach ($conflicts as $conflict) {
                        $conflict_ids[] = $conflict;
                    }

                }
            }
        }

        $conflict_ids = array_unique($conflict_ids);
        $conflict_orders = Order::whereIn('id', $conflict_ids)
        ->with('client')
        ->with('reservations.reservationable')->get();
        return ['order' => $order, 'conflict_orders' => $conflict_orders];
    }
}
