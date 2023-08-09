<template>
  <div id="allAirlines" class="flex flex-col items-center gap-y-6 m-12">
    <div
      v-for="airline in airlines"
      id="airline"
      class="flex flex-col items-center gap-y-6"
    >
      <div
        v-for="flight in airline['flights']"
        id="flight"
        class="border-2 border-black px-6 py-4"
      >
        <div id="airline">
          <p class="">{{ airline["airline"] }}</p>
          <hr class="border-black mb-2" />
        </div>
        <div id="timing" class="flex">
          <div id="departure" class="flex flex-col mr-36">
            <Location
              :dateTime="flight['departure_time']"
              :location="flight['origin']"
            />
          </div>
          <!-- computed the segment name -->
          <div v-if="flight['segments'].length > 1" class="segment">
            {{ flight["segments"][0]["origin"]["IATA"] }}
          </div>
          <div id="arrival" class="flex flex-col ml-36">
            <Location
              :dateTime="flight['arrival_time']"
              :location="flight['destination']"
            />
          </div>
          <div id="details" class="flex flex-col ml-12">
            <div id="duartion">{{ flight["duration"] }}</div>
            <div id="flightType">
              {{ flight["flight_type"] || flight["flight_key"] }}
            </div>
          </div>
        </div>
        <FareDropdown :travelFares="flight['TravelClass']"  />
        <DetailsDropdown :segments="flight['segments']" />
      </div>
    </div>
  </div>
</template>

<script setup>
const { data } = await useFetch("http://localhost:8000/api/getflights");
const airlines = data.value["data"];

console.log("flights");
</script>

<style></style>
