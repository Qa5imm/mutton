<?php

// function printdata($data)
// {
//     echo (json_encode($data));
// }


// Air Sial Mapper

use App\DataMapper\Airline;
use App\DataMapper\Baggage;
use App\DataMapper\Flight;
use App\DataMapper\PassengerClass;

$response = file_get_contents("./api.json");
$data = json_decode($response, true);
$airSialData = $data["airsial"];
$data = $airSialData["Response"]["Data"];

// printer($data);

enum classType: int
{
    case ADULT = 0;
    case CHILD  = 1;
    case INFANT = 2;
}



$flightData = $data["outbound"][0]; // variable

// printer($flightData);



$Flight = new Flight(
    null,
    $flightData['FLIGHT_NO'],
    $flightData['DEPARTURE_DATE'],
    $flightData['DEPARTURE_TIME'],
    $flightData['ARRIVAL_TIME'],
    $flightData['JOURNEY_CODE'],
    $flightData['ORGN'],
    $flightData['DEST'],
    $flightData['DURATION']
);
// dd($Flight);


// Baggage

$baggageData = $flightData["BAGGAGE_FARE"][0]; // variable
$Baggage = new Baggage($baggageData["abbr"], $baggageData["no_of_bags"], $baggageData["amount"], $baggageData["weight"]);
$Flight->setBaggage($Baggage);




// Passenger Class 

$classType = classType::from(0)->name;
$passengerClassData = $flightData["BAGGAGE_FARE"][0]["FARE_PAX_WISE"];
$PassengerClass = new PassengerClass(
    $classType,
    null,
    $passengerClassData[$classType]["TOTAL"],
    $flightData['CURRENCY'],
);
$Flight->setPassengerClass($PassengerClass);



dd($Flight);


// Airline
$Airline = new Airline("AirSial", "logoLink");
$Airline->setFlight($Flight);

