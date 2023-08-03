<?php

namespace App\DataMapper;

class Baggage
{
    public function __construct(
        protected $type,
        protected $bags_allowed,
        protected $amount,
        protected $weight
    ) {
    }
}
