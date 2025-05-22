<template>
  <div class="flex items-center space-x-16">
    <nav class="hidden lg:flex space-x-8 text-neutral-700 text-lg font-normal items-center">
      <UIGuestPartialsMenus />
    </nav>

    <div class="hidden lg:flex items-center space-x-6 cursor-pointer">
      <UILanguageSwitch />
      <CommonAppNuxtLink to="/signin" variant="primary">Sign In</CommonAppNuxtLink>
      <CommonAppNuxtLink to="/signup" variant="outline">Sign Up</CommonAppNuxtLink>
    </div>
  </div>
</template>

<script setup>

</script>

<style></style>