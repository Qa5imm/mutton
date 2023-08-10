<?php

namespace App\DataMapper;

use App\DataMapper\Airport;
use App\DataMapper\Segment;
use App\DataMapper\TravelClass;
use JsonSerializable;

class Flight implements JsonSerializable
{
    protected $TravelClass = [];
    protected $segments = [];
  

    public function __construct(
        protected String $aircraft,
        protected String $flight_type,
        protected String $flight_key,
        protected Airport $origin,
        protected Airport $destination,
        protected String $departure_date,
        protected String $departure_time,
        protected String $arrival_time,
        protected String $duration,
    ) {
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
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
