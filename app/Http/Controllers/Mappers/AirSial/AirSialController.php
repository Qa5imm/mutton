<?php

namespace App\Http\Controllers\Mappers\AirSial;


use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Baggage;
use App\DataMapper\Flight;
use App\DataMapper\PassengerClass;
use App\Http\Controllers\Controller;




enum classType
{
    case ADULT;
    case CHILD;
    case INFANT;
}
enum airportType
{
    case ORGN;
    case DEST;
}



class AirSialController extends Controller
{

    public function getAirlineData()
    {
        $response = file_get_contents("./api.json");
        $data = json_decode($response, true);
        $airSialData = $data["airsial"];
        $data = $airSialData["Response"]["Data"];

        $allFlights = $data["outbound"];

        // highest heirarchy object
        $Airline = new Airline("AirSial", "logo");

        foreach ($allFlights as $flightData) {
            $Flight = new Flight(
                null,
                null,
                $flightData['FLIGHT_NO'],
                $flightData['DEPARTURE_DATE'],
                $flightData['DEPARTURE_TIME'],
                $flightData['ARRIVAL_TIME'],
                $flightData['DURATION']
            );


            // Airports (origin and destination)
            foreach (airportType::cases() as $type) {
                $airport = new Airport(null, null, $flightData[$type->name]);
                $Flight->setAirport($type->name, $airport);
            }

            $allbaggages = $flightData["BAGGAGE_FARE"];
            foreach ($allbaggages as $baggageData) {
                $Baggage = new Baggage(
                    $baggageData["sub_class_desc"],
                    $baggageData["no_of_bags"],
                    $baggageData["amount"],
                    $baggageData["weight"]
                );
                $Flight->setBaggage($Baggage);
            }

            // Passenger Class 
            foreach (classType::cases() as $type) {
                $classType = $type->name;
                $passengerClassData = $flightData["BAGGAGE_FARE"][0]["FARE_PAX_WISE"];
                $PassengerClass = new PassengerClass(
                    $classType,
                    null,
                    $passengerClassData[$classType]["TOTAL"],
                    $flightData['CURRENCY'],
                );
                $Flight->setPassengerClass($PassengerClass);
            }


            // Airline
            $Airline->setFlight($Flight);
        }
        dd($Airline);
    }
}
