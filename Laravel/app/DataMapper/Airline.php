<?php

namespace App\DataMapper;

use JsonSerializable;


class Airline extends SetterClass implements JsonSerializable
{
    protected $flights = [];

    public function __construct(
        protected String $name,
        protected String $logo,
        protected $travllers,
        protected String $date,
    ) {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
