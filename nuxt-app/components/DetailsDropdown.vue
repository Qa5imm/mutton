<template>
  <div class="segemnets">
    <button
      id="button"
      class="mt-2 border-2 border-blue-500 text-white px-2 py-1 rounded-md bg-blue-500"
      @click="detailsDropdown = !detailsDropdown"
    >
      View Details
    </button>
    <div id="dropdown" class="mt-12" v-if="detailsDropdown">
      <div id="segment" v-for="n in 2" class="flex mb-6">
        <div id="departure" class="flex flex-col mr-36">
          <Location :time="'7pm'" :date="'12th JAN'" :location="'JED'" />
        </div>
        <div id="arrival" class="flex flex-col ml-36">
          <Location :time="'7pm'" :date="'12th JAN'" :location="'JED'" />
        </div>
        <div id="details" class="flex flex-col ml-12">
          <div id="duartion">0h 50m</div>
          <div id="flightType">Non Stop</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
const detailsDropdown = ref(false);
</script>
<style></style>
