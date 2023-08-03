<?php

namespace App\DataMapper;

use App\DataMapper\Flight;


class Airline
{
    protected $flights = [];
    public function __construct(protected $airline,  protected $logo)
    {
        $this->airline = $airline;
        $this->logo = $logo;
    }
    public function setFlight(Flight $flight)
    {
        $this->flights[] = $flight;
    }
}
