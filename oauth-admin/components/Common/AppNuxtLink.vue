<template>
  <NuxtLink
    :to="to"
    :class="computedClass"
  >
    <span class="text-base font-normal leading-tight font-poppins">
      <slot />
    </span>
  </NuxtLink>
</template>
  
<script setup>
const props = defineProps({
  to: { type: String, required: true },
  variant: { type: String, default: "primary" },
});

const computedClass = computed(() => {
  return props.variant === "primary"
    ? "bg-[#78057f] text-white hover:bg-[#6a006e]"
    : "bg-white border border-[#78057c] text-[#6a006e] hover:bg-[#f5e6f7]";
});
</script>

<style scoped>
.font-poppins {
  font-family: "Poppins", sans-serif;
}
</style>