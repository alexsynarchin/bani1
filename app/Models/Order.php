<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'status', 'price', 'type', 'date', 'start', 'end'];



    public function client()
    {
        return $this -> belongsTo(Client::class, 'client_id');
    }

    public function reservations()
    {
        return $this -> hasMany(Reservation::class, 'order_id');
    }
}
