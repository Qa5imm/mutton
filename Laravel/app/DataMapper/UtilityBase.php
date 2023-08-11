<?php

namespace App\DataMapper;

use Illuminate\Support\Str;
use Exception;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

class UtilityBase implements JsonSerializable
{
    // path mapper of all the array for which we need to check type
    private $classPath = [
        'flights' => 'App\DataMapper\Flight',
        'travelClasses' => 'App\DataMapper\TravelClass',
        'segments' => 'App\DataMapper\Segment',
        'fares' => 'App\DataMapper\Fare'
    ];
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            if ( // checking if it exists in classPath Mapper and if it does then assign the corresponding path
                array_key_exists($name, $this->classPath)
                && $value instanceof $this->classPath[$name]
            ) {
                $this->$name[] = $value;
            } else {
                $this->$name = $value;
            }
        } else {
            throw new Exception("Property you're trying to set doesn't exist" . $name);
        }
    }


    public function __get($name)
    {
        return $this->$name;
    }
    protected function formatClassName($name)
    {
        $singular = Str::Singular($name);
        $capitalizedName = Str::ucfirst($singular);
        return $capitalizedName;
    }
    protected function classNameResolutor($name)
    {
        $formattedName = $this->formatClassName($name);
        $class = "\App\DataMapper\\" . $formattedName;
        return $class;
    }
    public function jsonSerialize()
    {
        // return get_object_vars($this);
        // to only make only protected and private data members jsonSerializable
        $class = new ReflectionClass($this);
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        $seriableMembers = array();
        foreach ($properties as $property) {
            $propName = $property->getName();
            $seriableMembers[$propName] = $this->$propName;
        }
        return $seriableMembers;
    }
}
