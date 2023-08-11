<?php

namespace App\DataMapper;

use App\DataMapper\Airport;

class Flight extends UtilityBase
{
    protected $travelClasses = [];
    protected $segments = [];
    protected String $flight_type;
    protected Airport $origin;
    protected Airport $destination;
    protected String $departure_date = "";
    protected String $departure_time = "";
    protected String $arrival_time = "";
    protected String $duration = "";
    public function __construct()
    {
        $this->origin = new Airport();
        $this->destination = new Airport();
    }
}
