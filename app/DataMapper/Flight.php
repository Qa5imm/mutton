<?php

namespace App\DataMapper;

use App\DataMapper\Airport;
use App\DataMapper\Segment;
use App\DataMapper\TravelClass;

class Flight
{
    protected $TravelClass = [];
    protected $segments = [];
  

    public function __construct(
        protected $aircraft,
        protected $flight_type,
        protected $flight_key,
        protected Airport $origin,
        protected Airport $destination,
        protected $departure_date,
        protected $departure_time,
        protected $arrival_time,
        protected $duration,
    ) {
    }
    public function setTravelClass(TravelClass  $travelClass)
    {
        $this->TravelClass[] = $travelClass;
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
