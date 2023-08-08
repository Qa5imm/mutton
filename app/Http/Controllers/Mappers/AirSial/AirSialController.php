<?php


namespace App\Http\Controllers\Mappers\AirSial;

use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Fare;
use App\DataMapper\Flight;
use App\DataMapper\Segment;
use App\DataMapper\TravelClass;
use App\Http\Controllers\Controller;

include __DIR__ . '/../utils/utils.php';


enum passType
{
    case ADULT;
    case CHILD;
    case INFANT;
}
class AirSialController extends Controller
{

    public function getAirlineData()
    {
        $travellers = array(
            'ADULT' => array(
                'count' => 2
            ),
            'CHILD' => array(
                'count' => 1
            ),
            'INFANT' => array(
                'count' => 0
            ),
        );
        $response = file_get_contents("./api.json");
        $data = json_decode($response, true);
        $airSialData = $data["airsial"];
        $data = $airSialData["Response"]["Data"];

        $allFlights = $data["outbound"];

        //Airline- highest heirarchy object
        $dep_date = $allFlights[0]["DEPARTURE_DATE"];
        $Airline = new Airline("Aljazeera", "logo", $travellers, $dep_date);

        // Flight  
        foreach ($allFlights as $flightData) {
            $depTime =   $flightData['DEPARTURE_DATE'] . "T" . $flightData['DEPARTURE_TIME'];
            $arrTime = $flightData['DEPARTURE_DATE'] . "T" . $flightData['ARRIVAL_TIME'];
            $duration = calculateTimeDuration($depTime, $arrTime);
            $Flight = new Flight(
                null,
                null,
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
                    if ($travellers[$passType]['count'] !== 0) {
                        $totalAmount = $farePaxWise[$passType]["TOTAL"] * $travellers[$passType]['count']; // mutliplying base fare of a class with number of travellers in that class
                        $totalFare += $totalAmount;
                        $Fare = new Fare(
                            $passType,
                            $totalAmount
                        );
                        $TravelClass->setFares($Fare);
                    }
                }
                $TravelClass->setTotalFare($totalFare);
                $Flight->setTravelClass($TravelClass);
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
                    $duration
                );
                $Flight->setSegments($Segment);
            }

            // Airline
            $Airline->setFlight($Flight);
        }
        dd($Airline);
    }
}
