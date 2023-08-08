<?php

namespace App\Http\Controllers\Mappers\Aljazeera;

use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Fare;
use App\DataMapper\Flight;
use App\DataMapper\TravelClass;
use App\DataMapper\Segment;


include __DIR__ . '/../utils/utils.php';



class AljazeeraController
{


    public function getAirlineData()
    {
        // Economy Class Mapper
        $mapping = array(
            'EL' => 'Economy Light',
            'EV' => 'Economy Value',
            'EE' => 'Economy Extra',

        );

        $response = file_get_contents("./api.json");
        $data = json_decode($response, true);
        $aljazeeraData = $data["aljazeera"]["data"]["availabilityv4"];

        $trips = $aljazeeraData["results"][0]["trips"];
        $availableFares = $aljazeeraData["faresAvailable"];
        $currencyCode = $aljazeeraData["currencyCode"];

        $allFlights = $trips[0]["journeysAvailableByMarket"][0]["value"];
        $dep_date = $trips[0]["date"];


        // Airline
        $Airline = new Airline("Aljazeera", "logo");


        // Flight  
        foreach ($allFlights as $flightData) {
            $designator = $flightData["designator"];
            $depTime =   $designator["departure"];
            $arrTime =   $designator["arrival"];
            $duration = calculateTimeDuration($depTime, $arrTime);
            $Flight = new Flight(
                null,
                $flightData["flightType"],
                $flightData["journeyKey"],
                new Airport(null, null, $designator["origin"]),
                new Airport(null, null, $designator["destination"]),
                $dep_date,
                $depTime,
                $arrTime,
                $duration
            );


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
                        $mappedClass = $mapping[$class];
                        $weight = null;
                        $bags_allowed = null;
                        $currency = $currencyCode;
                        $TravelClass = new TravelClass(
                            $mappedClass,
                            $weight,
                            $bags_allowed,
                            $currency
                        );
                        $passengerFares = $fairValue["fares"][0]["passengerFares"];
                        foreach ($passengerFares as $passengerFare) {
                            $passengerType = $passengerFare["passengerType"];
                            $amount = $passengerFare["fareAmount"];
                            $Fare = new Fare(        //Fare
                                $passengerType,
                                $amount
                            );
                            $TravelClass->setFares($Fare);
                        }
                        $Flight->setTravelClass($TravelClass);
                    }
                }
            }


            // Segments
            $segments = $flightData["segments"];
            foreach ($segments as $segment) {
                $segDesignator = $segment["designator"];
                $Segment = new Segment(      //Segment
                    new Airport(null, null,  $segDesignator["origin"]),
                    new Airport(null, null,  $segDesignator["destination"]),
                    $segDesignator["arrival"],
                    $segDesignator["departure"],
                );
                $Flight->setSegments($Segment);
            }
            $Airline->setFlight($Flight);
        }
        dd($Airline);
    }
}
