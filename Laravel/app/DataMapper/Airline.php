<?php

namespace App\DataMapper;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

class Airline extends UtilityBase
{
    protected $flights = [];
    protected String $name;
    protected String $logo;
    protected $travellers;
    protected String $date;
}
