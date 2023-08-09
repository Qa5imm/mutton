<?php

namespace App\Http\Controllers\Mappers;

use App\Http\Controllers\Mappers\AirSial\AirSialController;
use App\Http\Controllers\Mappers\Aljazeera\AljazeeraController;

class MapperController
{
    protected $mappedData = [];
    protected $data = [];
    protected static $travellers = array(
        'ADULT' => array(
            'count' => 1
        ),
        'CHILD' => array(
            'count' => 1
        ),
        'INFANT' => array(
            'count' => 1
        )
    );
    public function getData()
    {
        $response = file_get_contents("./api.json");
        $data = json_decode($response, true);

        $this->mappedData[] = AljazeeraController::getAirlineData($data, self::$travellers);
        $this->mappedData[] = AirSialController::getAirlineData($data, self::$travellers);
        return response()->json(['data' => $this->mappedData]);
    }
}
