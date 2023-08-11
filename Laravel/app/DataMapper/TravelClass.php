<?php

namespace App\DataMapper;

class TravelClass extends UtilityBase
{
    protected $fares = [];
    protected float $total_fare;
    protected String $type;
    protected ?String $total_weight;
    protected ?String $bags_allowed;
    protected String $currency;
    public function __construct()
    {
        $this->total_weight = null;
        $this->bags_allowed = null;
    }
}
