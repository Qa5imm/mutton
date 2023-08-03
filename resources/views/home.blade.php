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

// function countEnum()
// {
//     return count(classType::Cases());
// }
$allFlights = $data["outbound"];

echo count($allFlights);
// dd($allFlights);
$Airline = new Airline("AirSial", "logoLink");


for ($i = 0; $i < count($allFlights); $i++) {
    $flightData = $allFlights[$i]; // variable
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
    // $allbaggages = $flightData["BAGGAGE_FARE"];

    // for ($j = 0; $j < count($allbaggages); $j++) {
    //     $baggageData = $allbaggages[$j]; // variable
    //     $Baggage = new Baggage(
    //         $baggageData["abbr"],
    //         $baggageData["no_of_bags"],
    //         $baggageData["amount"],
    //         $baggageData["weight"]
    //     );
    //     $Flight->setBaggage($Baggage);
    // }

    // Passenger Class 
    // for ($k = 0; $k < countEnum(); $k++) {
    //     $classType = classType::from($k)->name;
    //     $passengerClassData = $flightData["BAGGAGE_FARE"][$k]["FARE_PAX_WISE"];
    //     $PassengerClass = new PassengerClass(
    //         $classType,
    //         null,
    //         $passengerClassData[$classType]["TOTAL"],
    //         $flightData['CURRENCY'],
    //     );
    //     $Flight->setPassengerClass($PassengerClass);
    // }


    // dd($Flight);


    // Airline
    $Airline->setFlight($Flight);
}

dd($Airline);
