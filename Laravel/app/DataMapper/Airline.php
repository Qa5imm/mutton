<?php

namespace App\DataMapper;

use App\DataMapper\Flight;
use Illuminate\Support\Str;
use Exception;
use JsonSerializable;
use PDO;

function formatClassName($name)
{
    $singular = Str::Singular($name);
    $capitalizedName = Str::ucfirst($singular);
    return $capitalizedName;
}
function classNameResolutor($name)
{
    $formattedName = formatClassName($name);
    $class = "\App\DataMapper\\" . $formattedName;
    return $class;
}

class Airline implements JsonSerializable
{
    protected $flights = [];

    public function __construct(
        protected String $name,
        protected String $logo,
        protected $travllers,
        protected String $date,
    ) {
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $class = classNameResolutor($name);
            if ($value instanceof $class) {
                $this->$name[] = $value;
            } else {
                throw new Exception("Invalid Assignment");
            }
        } else {
            throw new Exception("Property you're trying to set doesn't exist");
        }
    }
}
