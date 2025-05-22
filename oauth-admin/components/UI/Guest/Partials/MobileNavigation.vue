<template>
  <div
    v-if="isMenuOpen"
    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-md flex items-center justify-center z-10"
    :style="{
      backgroundImage: 'url(/assets/img/background-img.png)',
      backgroundSize: 'cover',
      backgroundPosition: 'center',
    }"
  >
    <div
      class="relative bg-white w-4/5 max-w-sm rounded-lg shadow-lg p-6 flex flex-col items-center"
    >
      <!-- Close Button -->
      <button
          class="absolute top-4 right-4 text-gray-700"
          @click="$emit('close-menu')"
        >
        <IconsClose />
        </button>

      <!-- Mobile Navigation -->
      <nav class="flex flex-col space-y-4 text-center text-lg mt-6 w-full">
        <UIGuestPartialsMenus />
      </nav>

      <!-- Language Switch -->
      <UILanguageSwitch />

      <!-- Sign In & Sign Up Buttons -->
      <!-- <CommonAppNuxtLink to="/signin" variant="primary">Sign In</CommonAppNuxtLink>
      <CommonAppNuxtLink to="/signup" variant="outline">Sign Up</CommonAppNuxtLink> -->
    </div>
  </div>
</template>
  
<script setup>
defineProps({
  isMenuOpen: Boolean,
});

const emit = defineEmits(["close-menu"]);
</script>
  