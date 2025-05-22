<template>
  <div>
    <UIAdminDashboardComponentsLodaing />
  </div>
</template>

<script setup>
definePageMeta({ middleware: ["auth"], layout: "admin" });
</script>

<style scoped></style>
