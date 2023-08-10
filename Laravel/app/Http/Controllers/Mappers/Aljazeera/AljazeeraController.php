<?php

namespace App\Http\Controllers\Mappers\Aljazeera;

use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Fare;
use App\DataMapper\Flight;
use App\DataMapper\TravelClass;
use App\DataMapper\Segment;
use JsonSerializable;

include_once __DIR__ . '/../utils/utils.php';

// Economy Class Mapper




class AljazeeraController
{
    protected static $mapping = array(
        'EL' => 'Economy Light',
        'EV' => 'Economy Value',
        'EE' => 'Economy Extra',
        'BU' => 'Business'
    );
    protected static $travellers = array(
        'ADT' => array(
            'count' => 3
        ),
        'CHD' => array(
            'count' => 0
        ),
    );


    public static function getAirlineData($data, $travellers)
    {
        // setting travellers for the sake of it 
        self::$travellers['ADT']['count'] = $travellers['ADULT']['count'];
        self::$travellers['CHD']['count'] = $travellers['CHILD']['count'];


        $aljazeeraData = $data["aljazeera-multiple"]["data"]["availabilityv4"];

        $trips = $aljazeeraData["results"][0]["trips"];
        $availableFares = $aljazeeraData["faresAvailable"];
        $currencyCode = $aljazeeraData["currencyCode"];

        $allFlights = $trips[0]["journeysAvailableByMarket"][0]["value"];
        $depDate = $trips[0]["date"];
        $formattedDepDate = explode("T", $depDate)[0];

        //Airline- highest heirarchy object
        $Airline = new Airline("Aljazeera", "logo", self::$travellers, $formattedDepDate);


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
                        $mappedClass = self::$mapping[$class];
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
                            if (self::$travellers[$passengerType]['count'] !== 0) {
                                $totalAmount = $passengerFare["fareAmount"] * self::$travellers[$passengerType]['count'];
                                $totalFare += $totalAmount;
                                $Fare = new Fare(        //Fare
                                    $passengerType,
                                    round($totalAmount, 2)  // rounding upto 2 decimal places
                                );
                                $TravelClass->fares = $Fare;
                            }
                        }
                        $TravelClass->total_fare = round($totalFare, 2);

                        $Flight->travelClasses = $TravelClass;
                    }
                }
            }


            // Segments
            $segments = $flightData["segments"];
            foreach ($segments as $segment) {
                // higher level objects
                $segDesignator = $segment["designator"];
                $segLegInfo = $segment["legs"][0]["legInfo"];
                $segIdentifier = $segment['identifier'];

                // required attributes
                $depTime =   $segDesignator["departure"];
                $arrTime =   $segDesignator["arrival"];
                $aircraft = $segLegInfo["equipmentType"] . $segLegInfo["equipmentTypeSuffix"];
                $flightNumber = $segIdentifier["carrierCode"] . " " . $segIdentifier["identifier"];
                $duration = calculateTimeDuration($depTime, $arrTime);
                $Segment = new Segment(      //Segment
                    new Airport(null, null,  $segDesignator["origin"]),
                    new Airport(null, null,  $segDesignator["destination"]),
                    $depTime,
                    $arrTime,
                    $duration,
                    $flightNumber,
                    $aircraft
                );
                $Flight->segments = $Segment;
            }
            // using magic function
            $Airline->flights = $Flight;
        }
        return $Airline;
    }
}
