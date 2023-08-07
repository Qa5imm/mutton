<?php

namespace App\DataMapper;

class Fare
{
    public function __construct(
        protected $type,
        protected $amount,
    ) {
    }
}
