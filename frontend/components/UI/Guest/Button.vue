<template>
  <button
    :class="[
      'w-[120px] h-12 px-4 py-2 rounded flex justify-center items-center transition duration-300 border',
      borderColor !== 'transparent' ? 'border' : '',
      'hover:opacity-90',
      customClass, 
    ]"
    :style="{
      backgroundColor: bgColor,
      color: textColor,
      borderColor: borderColor,
    }"
    :type="type"
    @click="onClick"
  >
    <span class="text-base font-medium leading-tight font-poppins">
      {{ buttonText }}
    </span>
    <slot /> 
  </button>
</template>

<script setup>
defineProps({
  buttonText: { type: String, required: true },
  bgColor: { type: String, default: "#78057c" },
  hoverColor: { type: String, default: "#6a006e" },
  textColor: { type: String, default: "#ffffff" },
  borderColor: { type: String, default: "transparent" },
  type: { type: String, default: "button" },
  onClick: { type: Function, required: false },
  customClass: { type: String, default: "" }, 
});
</script>