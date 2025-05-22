<template>
  <header class="w-full h-20 bg-white shadow-md">
    <div class="container mx-auto px-4 flex justify-center items-center h-full">
      <!-- Desktop Navigation -->
      <UIGuestPartialsDesktopNavigation />

      <!-- Mobile Menu Button -->
      <button class="lg:hidden text-gray-700" @click="isMenuOpen = true">
        <IconsHamburger />
      </button>
    </div>

    <!-- Mobile Menu (Full-Screen Overlay) -->
    <UIGuestPartialsMobileNavigation :isMenuOpen="isMenuOpen" @close-menu="isMenuOpen = false" />
  </header>
</template>

<script setup>
const isMenuOpen = ref(false);
</script>

<style scoped>

</style>