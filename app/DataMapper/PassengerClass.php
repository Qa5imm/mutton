<?php

namespace App\DataMapper;


use App\DataMapper\Fare;


class PassengerClass
{
    protected $fares = [];
    public function __construct(
        protected $type,
        protected $seats_left,
        protected $weight,
        protected $bags_allowed, 
        protected $currency
    ) {
    }

    public function setFares(Fare $fare)
    {
        $this->fares[] = $fare;
    }
}
