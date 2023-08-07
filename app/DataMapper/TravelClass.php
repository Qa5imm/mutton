<?php

namespace App\DataMapper;


use App\DataMapper\Fare;
class TravelClass
{
    protected $fares = [];
    protected $total_fare;
    public function __construct(
        protected $class,
        protected $total_weight,
        protected $bags_allowed, 
        protected $currency,
    ) {
    }

    public function setFares(Fare $fare)
    {
        $this->fares[] = $fare;
    }

    public function setTotalFare(int $total){
        $this->total_fare= $total;
    }
}
