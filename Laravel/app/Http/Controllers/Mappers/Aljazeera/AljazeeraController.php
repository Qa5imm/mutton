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
        $Airline = new Airline();
        $Airline->name = "Aljazeera";
        $Airline->logo = "logo";
        $Airline->travellers = self::$travellers;
        $Airline->date = $formattedDepDate;


        // Flight  
        foreach ($allFlights as $flightData) {
            $designator = $flightData["designator"];
            $depTime =   $designator["departure"];
            $arrTime =   $designator["arrival"];
            $duration = calculateTimeDuration($depTime, $arrTime);
            $Flight = new Flight();
            $Flight->flight_type = $flightData["flightType"];
            $Flight->origin->IATA = $designator["origin"];
            $Flight->destination->IATA = $designator["destination"];
            $Flight->departure_date = $depDate;
            $Flight->departure_time = $depTime;
            $Flight->arrival_time = $arrTime;
            $Flight->duration = $duration;


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
                        $currency = $currencyCode;
                        // Travel Class
                        $TravelClass = new TravelClass();
                        $TravelClass->type =  $mappedClass;
                        $TravelClass->currency = $currency;

                        $passengerFares = $fairValue["fares"][0]["passengerFares"];
                        $totalFare = 0;
                        foreach ($passengerFares as $passengerFare) {
                            $passengerType = $passengerFare["passengerType"];
                            if (self::$travellers[$passengerType]['count'] !== 0) {
                                $totalAmount = $passengerFare["fareAmount"] * self::$travellers[$passengerType]['count'];
                                $totalFare += $totalAmount;
                                // Fare
                                $Fare = new Fare();
                                $Fare->passenger_type = $passengerType;
                                $Fare->amount = round($totalAmount, 2);  // rounding upto 2 decimal places

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
                // Segment
                $Segment = new Segment();
                $Segment->origin->IATA = $segDesignator["origin"];
                $Segment->destination->IATA = $segDesignator["destination"];
                $Segment->departure_time = $depTime;
                $Segment->arrival_time = $arrTime;
                $Segment->duration = $duration;
                $Segment->flight_number = $flightNumber;
                $Segment->aircraft = $aircraft;

                $Flight->segments = $Segment;
            }

            $Airline->flights = $Flight;
        }
        return $Airline;
    }
}
