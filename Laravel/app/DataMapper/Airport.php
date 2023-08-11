<?php

namespace App\DataMapper;


class Airport extends UtilityBase
{
    public function __construct(
        protected ?String $name,  //? to indicate it can be null
        protected ?String $country,
        protected String $IATA
    ) {
    }
}
