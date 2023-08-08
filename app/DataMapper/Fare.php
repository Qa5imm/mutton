<?php

namespace App\DataMapper;

use JsonSerializable;

class Fare implements JsonSerializable
{
    public function __construct(
        protected $passenger_type,
        protected $amount,
    ) {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
