<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Flight extends Model
{
    use HasFactory;
    
    /**
     * fillable
     *
    * @var array
     */
    protected $fillable = [
        'flight_number',
        'airline',
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'price',
        'seats_available',
        'airline_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
