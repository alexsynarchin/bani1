<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    use HasFactory;

    public function reservations()
    {
        return $this->morphMany(Reservation::class, 'reservationable');
    }
}
