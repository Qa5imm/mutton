<?php

namespace App\DataMapper;


use App\DataMapper\Fare;
use Exception;
use JsonSerializable;

class TravelClass implements JsonSerializable
{
    protected $fares = [];
    protected $total_fare = 0;
    public function __construct(
        protected String $type,
        protected ?String $total_weight,
        protected ?String $bags_allowed,
        protected String $currency,
    ) {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            if (gettype($this->$name) == "array") {
                $this->$name[] = $value;
            } else { //in case of a value
                $this->$name = $value;
            }
        } else {
            throw new Exception("Property you're trying to set doesn't exist");
        }
    }
}
