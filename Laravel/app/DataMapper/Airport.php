<?php

namespace App\DataMapper;


class Airport extends UtilityBase
{
    protected ?String $name; //? to indicate it can be null
    protected ?String $country;
    protected String $IATA;
    public function __construct()
    {
        $this->name=null;
        $this->country=null;
    }
}

