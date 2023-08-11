<?php

namespace App\DataMapper;

class Fare  extends UtilityBase
{
    public function __construct(
        protected String $passenger_type,
        protected float $amount,
    ) {
    }
}
