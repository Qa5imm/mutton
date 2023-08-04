<?php

function printdata($data)
{
    echo (json_encode($data));
}


// Air Sial Mapper

use App\DataMapper\Airline;
use App\DataMapper\Airport;
use App\DataMapper\Baggage;
use App\DataMapper\Flight;
use App\DataMapper\PassengerClass;

$response = file_get_contents("./api.json");
$data = json_decode($response, true);
$airSialData = $data["airsial"];
$data = $airSialData["Response"]["Data"];


enum classType: int
{
    case ADULT = 0;
    case CHILD  = 1;
    case INFANT = 2;
}
enum airportType
{
    case ORGN;
    case DEST;
}

function countEnum($type)
{
    if ($type === "class") {
        return count(classType::Cases());
    }
    if ($type === "airport") {
        return count(airportType::Cases());
    }
}



$allFlights = $data["outbound"];

// highest heirarchy object
$Airline = new Airline("AirSial", "logoLink");


for ($i = 0; $i < count($allFlights); $i++) {
    $flightData = $allFlights[$i];
    $Flight = new Flight(
        null,
        $flightData['FLIGHT_NO'],
        $flightData['DEPARTURE_DATE'],
        $flightData['DEPARTURE_TIME'],
        $flightData['ARRIVAL_TIME'],
        $flightData['JOURNEY_CODE'],
        $flightData['DURATION']
    );


    // Airports (origin and destination)
    foreach (airportType::cases() as $type) {
        $airport = new Airport(null, null, $flightData[$type->name]);
        $Flight->setAirport($type->name, $airport);
    }

    $allbaggages = $flightData["BAGGAGE_FARE"];

    for ($j = 0; $j < count($allbaggages); $j++) {
        $baggageData = $allbaggages[$j];
        $Baggage = new Baggage(
            $baggageData["sub_class_desc"],
            $baggageData["no_of_bags"],
            $baggageData["amount"],
            $baggageData["weight"]
        );
        $Flight->setBaggage($Baggage);
    }

    // Passenger Class 
    for ($k = 0; $k < countEnum("class"); $k++) {
        echo $k;
        $classType = classType::from($k)->name;
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
