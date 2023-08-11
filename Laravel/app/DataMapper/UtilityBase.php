<?php

namespace App\DataMapper;

use Illuminate\Support\Str;
use Exception;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

class UtilityBase implements JsonSerializable
{
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $invalid = true; // flag to catch invalid assignment
            if (gettype($this->$name) == "array") {
                $class = $this->classNameResolutor($name); // rescolve given string to class
                if ($value instanceof $class) { // checking type of the passed instance
                    $this->$name[] = $value;
                    $invalid = false;
                }
            } else { //in case of a non-object value (string, int etc)
                if (gettype($value) == gettype($this->$name)) { // checking type of the passed value
                    $this->$name = $value;
                    $invalid = false;
                }
            }
            if ($invalid) { // throwing Exception if $invalid flag is true
                throw new Exception("Invalid Assignment");
            }
        } else {
            throw new Exception("Property you're trying to set doesn't exist");
        }
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
