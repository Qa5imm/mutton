<?php

namespace App\Http\Controllers\Mappers\Aljazeera;

use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Fare;
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

        $trips = $aljazeeraData["results"][0]["trips"];
        $availableFares = $aljazeeraData["faresAvailable"];
        $currencyCode = $aljazeeraData["currencyCode"];

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
        $fares = $flightData["fares"];
        //Looping fares 
        foreach ($fares as $fare) {
            $fairKey = $fare["fareAvailabilityKey"];
            // Looping fares available - to find the fair mapped to fairKey
            foreach ($availableFares as $availableFare) {
                if ($availableFare["key"] === $fairKey) {
                    $fairValue = $availableFare["value"];
                    $class = $fairValue["fares"][0]["productClass"];
                    $weight = null;
                    $bags_allowed = null;
                    $currency = $currencyCode;
                    $PassengerClass = new PassengerClass(
                        $class,
                        $weight,
                        $bags_allowed,
                        $currency
                    );
                    $passengerFares = $fairValue["fares"][0]["passengerFares"];
                    foreach ($passengerFares as $passengerFare) {
                        // dd($passengerFare);
                        $passengerType = $passengerFare["passengerType"];
                        $amount = $passengerFare["fareAmount"];
                        $Fare = new Fare(
                            $passengerType,
                            $amount
                        );
                        $PassengerClass->setFares($Fare);
                    }
                    $Flight->setPassengerClass($PassengerClass);
                }
            }
        }






        dd($Flight);
        return $availableFares;
    }
}
