<?php

namespace App\DataMapper;

use JsonSerializable;

class Airport implements JsonSerializable
{
    public function __construct(
        protected String $name,
        protected String $country,
        protected String $IATA
    ) {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
