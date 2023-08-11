<?php

namespace App\DataMapper;

use App\DataMapper\Airport;

class Flight extends UtilityBase 
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
}
