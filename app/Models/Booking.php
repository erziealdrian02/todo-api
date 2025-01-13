<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Booking extends Model
{
    use HasFactory;
    
    /**
     * fillable
     *
    * @var array
     */
    protected $fillable = [
        'user_id',
        'flight_id',
        'total_price',
        'payment_status',
        'booking_date',
    ];
}
