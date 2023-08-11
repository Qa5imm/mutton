<?php

namespace App\DataMapper;

use App\DataMapper\Airport;

class Segment  extends UtilityBase
{
    protected Airport $origin;
    protected Airport $destination;
    protected String $departure_time;
    protected String $arrival_time;
    protected String $duration;
    protected String $flight_number;
    protected ?String $aircraft;
    public function __construct()
    {
        $this->origin = new Airport();
        $this->destination = new Airport();
        $this->aircraft= null;
    }
}
