<template>
  <div class="fares">
    <button
      id="button"
      class="mt-2 border-2 border-blue-500 text-white px-2 py-1 rounded-md bg-blue-500"
      @click="fareDropdown = !fareDropdown"
    >
      View Fares
    </button>
    <div id="dropdown" class="mt-12" v-if="fareDropdown">
      <div id="fare" class="flex justify-between mb-6">
        <div v-for="n in 2" id="class" class="flex flex-col">
          <p id="type">{{ props.type }}</p>
          <p id="currency">{{ props.currency }}</p>
          <p id="amount">{{ props.amount }}</p>
          <button class="text-sm bg-yellow-500 px-2 py-1 rounded-lg">
            Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
const fareDropdown = ref(false);
const props = defineProps({
  type: String,
  currency: String,
  amount: Number,
});
</script>
<style></style>
