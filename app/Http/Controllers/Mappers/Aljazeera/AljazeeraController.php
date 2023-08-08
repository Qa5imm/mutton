<?php

namespace App\Http\Controllers\Mappers\Aljazeera;

use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Fare;
use App\DataMapper\Flight;
use App\DataMapper\TravelClass;
use App\DataMapper\Segment;


include __DIR__ . '/../utils/utils.php';

// Economy Class Mapper




class AljazeeraController
{
    protected $mapping = array(
        'EL' => 'Economy Light',
        'EV' => 'Economy Value',
        'EE' => 'Economy Extra',
        'BU' => 'Business'
    );
    protected  $travellers = array(
        'ADT' => array(
            'count' => 2
        ),
        'CHD' => array(
            'count' => 1
        ),
    );

    public function getAirlineData()
    {

        $response = file_get_contents("./api.json");
        $data = json_decode($response, true);
        $aljazeeraData = $data["aljazeera-multiple"]["data"]["availabilityv4"];

        $trips = $aljazeeraData["results"][0]["trips"];
        $availableFares = $aljazeeraData["faresAvailable"];
        $currencyCode = $aljazeeraData["currencyCode"];

        $allFlights = $trips[0]["journeysAvailableByMarket"][0]["value"];
        $depDate = $trips[0]["date"];
        $formattedDepDate = explode("T", $depDate)[0];

        //Airline- highest heirarchy object
        $Airline = new Airline("Aljazeera", "logo", $this->travellers, $formattedDepDate);


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
                $depDate,
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
                        $mappedClass = $this->mapping[$class];
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
                        $totalFare = 0;
                        foreach ($passengerFares as $passengerFare) {
                            $passengerType = $passengerFare["passengerType"];
                            $totalAmount = $passengerFare["fareAmount"] * $this->travellers[$passengerType]['count'];
                            $totalFare += $totalAmount;
                            $Fare = new Fare(        //Fare
                                $passengerType,
                                $totalAmount
                            );
                            $TravelClass->setFares($Fare);
                        }
                        $TravelClass->setTotalFare($totalFare);
                        $Flight->setTravelClass($TravelClass);
                    }
                }
            }


            // Segments
            $segments = $flightData["segments"];
            foreach ($segments as $segment) {
                $segDesignator = $segment["designator"];
                $depTime =   $segDesignator["departure"];
                $arrTime =   $segDesignator["arrival"];
                $duration = calculateTimeDuration($depTime, $arrTime);
                $Segment = new Segment(      //Segment
                    new Airport(null, null,  $segDesignator["origin"]),
                    new Airport(null, null,  $segDesignator["destination"]),
                    $depTime,
                    $arrTime,
                    $duration

                );
                $Flight->setSegments($Segment);
            }
            $Airline->setFlight($Flight);
        }
        dd($Airline);
    }
}
