<?php

namespace App\DataMapper;

use JsonSerializable;

class TravelClass extends SetterClass implements JsonSerializable
{
    protected $fares = [];
    protected float $total_fare = 0;
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
}
