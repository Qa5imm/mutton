<?php

namespace App\DataMapper;

class PassengerClass
{
    public function __construct(protected $type, protected $seats_left, protected $fair, protected $currency)
    {
    }
}
