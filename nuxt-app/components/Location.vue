<template>
  <p id="time">{{ time }}</p>
  <p id="date">{{ date }}</p>
  <p id="location">{{ location }}</p>
</template>
<script setup>
const props = defineProps({
  dateTime: String,
  location: String,
});

const time= props.dateTime.split("T")[1];
const date= props.dateTime.split("T")[0];
const location= props.location["IATA"];
</script>
<style></style>
