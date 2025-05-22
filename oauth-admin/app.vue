<template>
  <div>
    <UILoading ref="loader" />
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
    <UINotification />
  </div>
</template>

<script setup>
const loader = ref(null);

onMounted(() => {
  loader.value?.show();
});

onNuxtReady(() => {
  loader.value?.hide();
});
</script>


