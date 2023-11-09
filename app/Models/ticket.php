<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ticket extends Model
{
    protected $casts = [
        'extra_details' => 'array',
    ];

    public function passengers()
    {
        return $this->hasMany(passenger::class);
    }

    public function agency()
    {
        return $this->belongsTo(agency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outboundFlight()
    {
        return $this->belongsTo(FlightData::class, 'outbound_flight_id');
    }

    public function returnFlight()
    {
        return $this->belongsTo(FlightData::class, 'return_flight_id');
    }
}
