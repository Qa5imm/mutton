<?php

namespace App\DataMapper;

class TravelClass extends UtilityBase
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
}
