<?php

namespace App\DataMapper;
use JsonSerializable;

class Fare implements JsonSerializable
{
    public function __construct(
        protected String $passenger_type,
        protected float $amount,
    ) {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
