<template>
  <div class="bg-gray-900">
    <UICommonLoading ref="loader" />
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
    <UINotification />
  </div>
</template>

<script setup>
useHead({
  title: "Frontend",
  meta: [
    { charset: "utf-8" },
    { name: "viewport", content: "width=device-width, initial-scale=1.0" },
  ],
  link: [
    {
      rel: "stylesheet",
      href: "https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap",
    },
  ],
});
const loader = ref(null);

onMounted(() => {
  loader.value?.show();
});

onNuxtReady(() => {
  loader.value?.hide();
});
</script>
