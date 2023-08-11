<?php

namespace App\Http\Controllers\Mappers;

use App\Http\Controllers\Mappers\AirSial\AirSialController;
use App\Http\Controllers\Mappers\Aljazeera\AljazeeraController;
use Illuminate\Support\Facades\Http;

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
        //  external api
        $response= Http::get('http://localhost:7000/all');
        $data = json_decode($response, true);
        $this->mappedData[] = AljazeeraController::getAirlineData($data, self::$travellers);
        $this->mappedData[] = AirSialController::getAirlineData($data, self::$travellers);
        return response()->json(['data' => $this->mappedData]);
    }
}
