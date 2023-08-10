<?php

namespace App\DataMapper;

use App\DataMapper\Airport;
use App\DataMapper\Segment;
use App\DataMapper\TravelClass;
use Exception;
use JsonSerializable;

class Flight implements JsonSerializable
{
    protected $travelClasses = [];
    protected $segments = [];


    public function __construct(
        protected ?String $aircraft, //? to indicate it can be null
        protected String $flight_type,
        protected String $flight_number,
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
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name[] = $value;
        } else {
            throw new Exception("Property you're trying to set doesn't exist");
        }
    }
}
