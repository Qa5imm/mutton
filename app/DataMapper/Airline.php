<?php

namespace App\DataMapper;

use App\DataMapper\Flight;


class Airline
{
    protected $flights = [];
    public function __construct(protected $airline,  protected $logo, protected $travllers, protected $date)
    {
    }
    public function setFlight(Flight $flight)
    {
        $this->flights[] = $flight;
    }
}
