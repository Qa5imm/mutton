<?php

namespace App\DataMapper;

class Fare
{
    public function __construct(
        protected $passenger_type,
        protected $amount,
    ) {
    }
}
