<template>
  <div class="text-center">
    <h1 class="text-4xl font-bold text-gray-900 mt-2">
      Welcome to OAuth2 Admin
    </h1>
  </div>
</template>

<script setup>
definePageMeta({ middleware: ["guest"] });
</script>

<style scoped>

</style>