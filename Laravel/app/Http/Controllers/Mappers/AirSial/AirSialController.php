<?php


namespace App\Http\Controllers\Mappers\AirSial;

use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Fare;
use App\DataMapper\Flight;
use App\DataMapper\Segment;
use App\DataMapper\TravelClass;
use App\Http\Controllers\Controller;

include_once __DIR__ . '/../utils/utils.php';


enum passType
{
    case ADULT;
    case CHILD;
    case INFANT;
}




class AirSialController extends Controller
{
    protected static $travellers = array(
        'ADULT' => array(
            'count' => 3
        ),
        'CHILD' => array(
            'count' => 0
        ),
        'INFANT' => array(
            'count' => 0
        )
    );

    public static function getAirlineData($data, $travellers)
    {
        $airSialData = $data["airsial"];
        $data = $airSialData["Response"]["Data"];

        $allFlights = $data["outbound"];

        //Airline- highest heirarchy object
        $dep_date = $allFlights[0]["DEPARTURE_DATE"];
        $Airline = new Airline("Airsial", "logo", self::$travellers, $dep_date);

        // Flight  
        foreach ($allFlights as $flightData) {
            $depTime =   $flightData['DEPARTURE_DATE'] . "T" . $flightData['DEPARTURE_TIME'];
            $arrTime = $flightData['DEPARTURE_DATE'] . "T" . $flightData['ARRIVAL_TIME'];
            $duration = calculateTimeDuration($depTime, $arrTime);
            $Flight = new Flight(
                null,
                'NonStop',
                $flightData['FLIGHT_NO'],
                new Airport(null, null, $flightData["ORGN"]),
                new Airport(null, null, $flightData["DEST"]),
                $flightData['DEPARTURE_DATE'],
                $depTime,
                $arrTime,
                $duration
            );

            // Passenger Class 
            $baggeFares = $flightData["BAGGAGE_FARE"];
            //Looping fares 
            foreach ($baggeFares as $baggeFare) {
                $TravelClass = new TravelClass(
                    $baggeFare["sub_class_desc"],
                    $baggeFare["weight"],
                    $baggeFare["no_of_bags"],
                    $flightData['CURRENCY']
                );
                $farePaxWise = $baggeFare["FARE_PAX_WISE"];
                $totalFare = 0;
                foreach (passType::cases() as $type) {
                    $passType = $type->name;
                    if (self::$travellers[$passType]['count'] !== 0) {
                        $totalAmount = $farePaxWise[$passType]["TOTAL"] * self::$travellers[$passType]['count']; // mutliplying base fare of a class with number of travellers in that class
                        $totalFare += $totalAmount;
                        $Fare = new Fare(        //Fare
                            $passType,
                            self::rounder($totalAmount, 2) // rounding upto 2 decimal places
                        );
                        $TravelClass->fares = $Fare;
                    }
                }
                $TravelClass->total_fare = self::rounder($totalFare, 2);
                $Flight->travelClasses = $TravelClass;
            }


            if (!isset($flightData["segments"])) {
                $depTime = $flightData['DEPARTURE_DATE'] . "T" . $flightData['DEPARTURE_TIME'];
                $arrTime = $flightData['DEPARTURE_DATE'] . "T" . $flightData['ARRIVAL_TIME'];
                $duration = calculateTimeDuration($depTime, $arrTime);
                $Segment = new Segment(
                    new Airport(null, null, $flightData["ORGN"]),
                    new Airport(null, null, $flightData["DEST"]),
                    $depTime,
                    $arrTime,
                    $duration,
                    $flightData["FLIGHT_NO"],
                    null
                );
                $Flight->segments = $Segment;
            }

            // Airline
            $Airline->flights = $Flight;
        }
        return $Airline;
    }
    public static function rounder($amount, $spaces)
    { // returns float, same as type defined in travel Class
        return round($amount, $spaces);
    }
}
