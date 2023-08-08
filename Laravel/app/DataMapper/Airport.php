<?php

namespace App\DataMapper;

use JsonSerializable;

class Airport implements JsonSerializable
{
    public function __construct(protected $name, protected $country, protected $IATA)
    {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
