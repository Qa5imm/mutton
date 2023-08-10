<?php

namespace App\DataMapper;

use App\DataMapper\Flight;
use Exception;
use JsonSerializable;

class Airline implements JsonSerializable
{
    protected $flights = [];
    public function __construct(
        protected String $name,
        protected String $logo,
        protected $travllers,
        protected String $date,
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
