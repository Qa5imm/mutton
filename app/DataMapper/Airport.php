<?php

namespace App\DataMapper;

class Airport
{
    public function __construct(protected $name, protected $country, protected $IATA)
    {
    }
}
