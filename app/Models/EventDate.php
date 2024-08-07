<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'start_time', 'day_off'];
    protected $casts = [
        'day_off' => 'boolean'
    ];
}
