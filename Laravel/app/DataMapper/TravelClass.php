<?php

namespace App\DataMapper;


use App\DataMapper\Fare;
use JsonSerializable;

class TravelClass implements JsonSerializable
{
    protected $fares = [];
    protected $total_fare;
    public function __construct(
        protected String $type,
        protected String $total_weight,
        protected String $bags_allowed,
        protected String $currency,
    ) {
    }

    public function setFares(Fare $fare)
    {
        $this->fares[] = $fare;
    }

    public function setTotalFare(float $total)
    {
        $this->total_fare = $total;
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
