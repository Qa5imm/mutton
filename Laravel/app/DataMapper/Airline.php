<?php

namespace App\DataMapper;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

class Airline extends SetterClass 
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

    // public function jsonSerialize()
    // {
    //     // to only make only protected and private data members jsonSerializable
    //     $class = new ReflectionClass($this);
    //     $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
    //     $seriableMembers = array();
    //     foreach ($properties as $property) {
    //         $propName = $property->getName();
    //         $seriableMembers[$propName] = $this->$propName;
    //     }
    //     return $seriableMembers;
    //     // return get_object_vars($this);
    // }
}
