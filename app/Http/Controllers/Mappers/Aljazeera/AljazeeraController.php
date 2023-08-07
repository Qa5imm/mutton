<?php

namespace App\Http\Controllers\Mappers\Aljazeera;

use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Flight;
use App\DataMapper\PassengerClass;


abstract class classType
{
    const EL = "Economy Light";
    const EV = "Economy Value";
    const EE = "Economy Extra";
}
enum airportType
{
    case origin;
    case destination;
}




class AljazeeraController
{
    public function getAirlineData()
    {
        $response = file_get_contents("./api.json");
        $data = json_decode($response, true);
        $aljazeeraData = $data["aljazeera"]["data"]["availabilityv4"];

        $availableFairs = $aljazeeraData["faresAvailable"];
        $trips = $aljazeeraData["results"][0]["trips"];

        $allFlights = $trips[0]["journeysAvailableByMarket"][0]["value"];


        $dep_date = $trips[0]["date"];
        $Airline = new Airline("Aljazeera", "logo");



        // Flight Data 
        $flightData = $allFlights[0];
        $designator = $flightData["designator"];
        $Flight = new Flight(
            null,
            $flightData["flightType"],
            $flightData["journeyKey"],
            $dep_date,
            $designator["departure"],
            $designator["arrival"],
            "1h"
        );

        // Airports (origin and destination)
        foreach (airportType::cases() as $type) {
            $airport = new Airport(null, null, $designator[$type->name]);
            $Flight->setAirport($type->name, $airport);
        }


        // PassengerClass
        $passengerClassData = $flightData["fares"];



        foreach ($passengerClassData as $data) {
            $fairKey = $data["fareAvailabilityKey"];




            foreach ($availableFairs as $fair) {
                if ($fair["key"] === $fairKey) {
                    $fairValue = $fair["value"];
                    $amount =  $fairValue["totals"]["fareTotal"];
                    $currency = $fairValue["fares"][0]["passengerFares"][0]["serviceCharges"][0]["currencyCode"];
                    $type = $fairValue["fares"][0]["productClass"];
                    // $mappedType = isset(classType::$$type) ? classType::$$type : $type;
                    // echo $mappedType;
                }
            }
            // $PassengerClass = new PassengerClass(
            //     $type,
            //     $data["details"][0]["availableCount"],
            //     $amount,
            //     $currency
            // );
            // $Flight->setPassengerClass($PassengerClass);


            // $availableFairs= 
            // echo $fairKey;
        }






        dd($Flight);
        return $availableFairs;
    }
}
