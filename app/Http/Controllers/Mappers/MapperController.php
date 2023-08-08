<?php

namespace App\Http\Controllers\Mappers;

use App\Http\Controllers\Mappers\AirSial\AirSialController;
use App\Http\Controllers\Mappers\Aljazeera\AljazeeraController;

class MapperController
{
    protected $mappedData = [];
    protected $data = [];
    public function getData()
    {
        $response = file_get_contents("./api.json");
        $data = json_decode($response, true);

        $this->mappedData[] = AljazeeraController::getAirlineData($data);
        $this->mappedData[] = AirSialController::getAirlineData($data);        
        return response()->json(['data' => $this->mappedData]);
    }
}
