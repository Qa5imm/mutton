<?php

namespace App\DataMapper;

use App\DataMapper\Airport;
use App\DataMapper\Baggage;
use App\DataMapper\Segment;
use App\DataMapper\PassengerClass;

class Flight
{
    protected $passengerClass = [];
    protected $baggage = [];
    protected $segments = [];
    protected Airport $origin;
    protected Airport $destination;

    public function __construct(
        protected $aircraft,
        protected $flight_type,
        protected $flight_key,
        protected $departure_date,
        protected $departure_time,
        protected $arrival_time,
        protected $duration,
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
    public function setAirport($type, Airport $airport) // to set origin and destination
    {
        if ($type === "DEST" || $type === "destination") {
            $this->destination = $airport;
        } else if ($type === "ORGN" || $type === "origin") {
            $this->origin = $airport;
        }
    }
}
