<?php

namespace App\DataMapper;
 
use App\DataMapper\Flight;
use JsonSerializable;

class Airline implements JsonSerializable
{
    protected $flights = [];
    public function __construct(protected $airline,  protected $logo, protected $travllers, protected $date)
    {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
    public function setFlight(Flight $flight)
    {
        $this->flights[] = $flight;
    }
}
