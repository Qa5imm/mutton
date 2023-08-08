<?php

namespace App\DataMapper;
use App\DataMapper\Airport;
class Segment
{
    public function __construct(
        protected Airport $origin,
        protected Airport $dest,
        protected $departure,
        protected $arrival,
        protected $duration
    ) {
    }
}
