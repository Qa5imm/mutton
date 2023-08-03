<?php

namespace App\DataMapper;

class Segment
{
    public function __construct(
        protected $origin,
        protected $dest,
        protected $arrival,
        protected $departure
    ) {
    }
}
