<?php

namespace App\DataMapper;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

class Airline extends UtilityBase 
{
    protected $flights = [];

    private String $yellow = "qasim";
    protected String $purple = "ali";
    public function __construct(
        protected String $name,
        protected String $logo,
        protected $travllers,
        protected String $date,
    ) {
    }

}
