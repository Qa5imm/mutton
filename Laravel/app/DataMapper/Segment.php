<?php

namespace App\DataMapper;
use App\DataMapper\Airport;
use JsonSerializable;

class Segment implements JsonSerializable
{
    public function __construct(
        protected Airport $origin,
        protected Airport $destination,
        protected $departure_time,
        protected $arrival_time,
        protected $duration
    ) {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
