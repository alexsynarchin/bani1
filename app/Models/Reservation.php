<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'start', 'end', 'reservationable_type, reservationable_id', 'date'];
    protected $appends = [
        'status', 'start_time', 'end_time', 'price'
    ];
    public function order()
    {
        return $this -> belongsTo(Order::class, 'order_id');
    }

    public function reservationable()
    {
        return $this->morphTo();
    }
    public function getStatusAttribute()
    {
        return $this -> order -> status;
    }
    public function getStartTimeAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this -> start)-> format('H:i');
    }
    public function getEndTimeAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this -> end)-> format('H:i');
    }

    public function getPriceAttribute()
    {
        $start  = new Carbon ($this->start);
        $end = new Carbon ($this->end);
        $diff = $end->diffInMinutes($start);
        $cost = $this->reservationable->price;
        $sum = 0;
        if ($diff > 120 && $this->reservationable_type === 'App\Models\Place') {
            $sum = 1100;
            $diff = $diff - 120;
            $sum = $sum + (450 * $diff) / 60;
        } else {
            $sum = ($cost * $diff) / 60;
        }


        return $sum;
    }
}
