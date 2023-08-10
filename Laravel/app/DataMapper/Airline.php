<?php

namespace App\DataMapper;

use App\DataMapper\Flight;
use JsonSerializable;
use PhpParser\Node\Expr\Cast\String_;


class Airline implements JsonSerializable
{
    protected $flights = [];
    public function __construct(
        protected String $name,
        protected String $logo,
        protected $travllers,
        protected String $date
    ) {
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
