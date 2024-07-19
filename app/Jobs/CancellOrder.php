<?php

namespace App\Jobs;

use App\Mail\OrderMail;
use App\Models\Order;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CancellOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $data = array(
            'token' => urldecode(env('ALFA_TOKEN')),
            //'userName' => env('ALFA_USERNAME'),
            //'password' => env('ALFA_PASSWORD'),
            'orderId' => $this->order->alfa_order_id
        );
        $alfa_pay = new PaymentService();
        $response = $alfa_pay -> gateway('getOrderStatus.do', $data);

        if($response['OrderStatus'] === 2) {
            $this->order -> status = 'success';
            $this->order->save();
            $emails = [
                'order@baniufa.ru', 'ufar87@mail.ru', 'gwynbleid11@yandex.ru'
            ];
            foreach ($emails as $email) {
                Mail::to($email)->send(new OrderMail($this->order));
            }
        } elseif($response['OrderStatus'] === 0 || $response['OrderStatus'] === 5) {
           $expired_time = Carbon::parse($this->order->created_at)->addMinutes(24);
           $expired_time = $expired_time->getTimestamp();

           if($expired_time > Carbon::now()->getTimestamp()) {
               CancellOrder::dispatch($this->order)->delay(now()->addMinutes(2));
           } else {
               $this->order -> status = 'cancelled';
               $this->order->save();
           }

        } else {
                $this->order -> status = 'cancelled';
                $this->order->save();
        }

    }
}
