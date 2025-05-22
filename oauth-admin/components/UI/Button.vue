<template>
  <button
    :type="type"
    :disabled="isLoading"
    :class="buttonClasses"
    class="w-full bg-blue-500 text-white py-2 rounded-md text-lg font-medium hover:bg-blue-600 transition"
  >
    <span v-if="isLoading">
      <IconsLoding />
      Loading...
    </span>
    <span v-else-if="isSuccess" class="text-green-500">Success!</span>
    <span v-else>{{ buttonText }}</span>
  </button>
</template>

<script setup>
const props = defineProps({
  buttonText: {
    type: String,
    default: "Submit",
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
  isSuccess: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: "button",
  },
  color: {
    type: String,
    default: "blue",
  },
});

const buttonClasses = computed(() => {
  switch (props.color) {
    case "blue":
      return "bg-blue-500 hover:bg-blue-700 text-white";
    case "gray":
      return "bg-gray-300 hover:bg-gray-400 text-gray-700";
    case "green":
      return "bg-green-500 hover:bg-green-700 text-white";
    case "red":
      return "bg-red-500 hover:bg-red-700 text-white";
    default:
      return "bg-blue-500 hover:bg-blue-700 text-white";
  }
});
</script>

<style scoped>
/* Add any specific styles for the button here */
</style>
