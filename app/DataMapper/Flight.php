<?php

namespace App\DataMapper;

use App\DataMapper\Baggage;
use App\DataMapper\Segment;
use App\DataMapper\PassengerClass;

class Flight
{
    protected $passengerClass = [];
    protected $baggage = [];
    protected $segments = [];

    public function __construct(
        protected $aircraft,
        protected $flight_key,
        protected $departure_date,
        protected $departure_time,
        protected $arrival_time,
        protected $origin,
        protected $destination,
        protected $duration,
        protected $flight_type
    ) {
    }
    public function setPassengerClass(PassengerClass  $passengerClass)
    {
        $this->passengerClass[] = $passengerClass;
    }
    public function setBaggage(Baggage $baggage)
    {
        $this->baggage[] = $baggage;
    }

    public function setSegments(Segment $segments)
    {
        $this->segments[] = $segments;
    }

    public static function fromState(array $state)
    {
        return "object";
    }
}

