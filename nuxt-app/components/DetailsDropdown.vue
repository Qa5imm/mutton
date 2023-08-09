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
      <div id="segment" v-for="segment in props.segments" class="flex mb-6">
        <div id="departure" class="flex flex-col mr-36">
          <Location
            :dateTime="segment['departure_time']"
            :location="segment['origin']"
          />
        </div>
        <div id="arrival" class="flex flex-col ml-36">
          <Location
            :dateTime="segment['arrival_time']"
            :location="segment['destination']"
          />
        </div>
        <div id="details" class="flex flex-col ml-12">
          <div id="duartion">{{ segment["duration"] }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps(["segments"]);

const detailsDropdown = ref(false);
</script>
<style></style>
