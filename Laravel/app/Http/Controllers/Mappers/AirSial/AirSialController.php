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
        $Airline = new Airline();
        $Airline->name = "Airsial";
        $Airline->logo = "logo";
        $Airline->travellers = self::$travellers;
        $Airline->date = $dep_date;

        foreach ($allFlights as $flightData) {
            $depTime =   $flightData['DEPARTURE_DATE'] . "T" . $flightData['DEPARTURE_TIME'];
            $arrTime = $flightData['DEPARTURE_DATE'] . "T" . $flightData['ARRIVAL_TIME'];
            $duration = calculateTimeDuration($depTime, $arrTime);
            // Flight  
            $Flight = new Flight();
            $Flight->flight_type = 'NonStop';
            $Flight->origin->IATA = $flightData["ORGN"];
            $Flight->destination->IATA = $flightData["DEST"];
            $Flight->departure_date = $flightData['DEPARTURE_DATE'];
            $Flight->departure_time = $depTime;
            $Flight->arrival_time = $arrTime;
            $Flight->duration = $duration;

            // Passenger Class 
            $baggeFares = $flightData["BAGGAGE_FARE"];
            //Looping fares 
            foreach ($baggeFares as $baggeFare) {
                // Travel Class
                $TravelClass = new TravelClass();
                $TravelClass->type =  $baggeFare["sub_class_desc"];
                $TravelClass->currency = $flightData['CURRENCY'];
                $TravelClass->total_weight = $baggeFare["weight"];
                $TravelClass->bags_allowed = $baggeFare["no_of_bags"];


                $farePaxWise = $baggeFare["FARE_PAX_WISE"];
                $totalFare = 0;
                foreach (passType::cases() as $type) {
                    $passType = $type->name;
                    if (self::$travellers[$passType]['count'] !== 0) {
                        $totalAmount = $farePaxWise[$passType]["TOTAL"] * self::$travellers[$passType]['count']; // mutliplying base fare of a class with number of travellers in that class
                        $totalFare += $totalAmount;
                        //Fare
                        $Fare = new Fare();
                        $Fare->passenger_type = $passType;
                        $Fare->amount = round($totalAmount, 2);  // rounding upto 2 decimal places

                        $TravelClass->fares = $Fare;
                    }
                }
                $TravelClass->total_fare = round($totalFare, 2);
                $Flight->travelClasses = $TravelClass;
            }


            if (!isset($flightData["segments"])) {
                $depTime = $flightData['DEPARTURE_DATE'] . "T" . $flightData['DEPARTURE_TIME'];
                $arrTime = $flightData['DEPARTURE_DATE'] . "T" . $flightData['ARRIVAL_TIME'];
                $duration = calculateTimeDuration($depTime, $arrTime);
                // Segment
                $Segment = new Segment();
                $Segment->origin->IATA = $flightData["ORGN"];
                $Segment->destination->IATA = $flightData["DEST"];
                $Segment->departure_time = $depTime;
                $Segment->arrival_time = $arrTime;
                $Segment->duration = $duration;
                $Segment->flight_number = $flightData["FLIGHT_NO"];

                $Flight->segments = $Segment;
            }

            // Airline
            $Airline->flights = $Flight;
        }
        return $Airline;
    }
}
