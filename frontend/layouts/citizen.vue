<template>
  <BaseLayout>
    <template #header>
      <UICitizenHeader name="citizen-header" />
    </template>

    <div class="flex-grow flex container mx-auto my-4 px-4">
      <slot></slot>
    </div>
    
    <template #footer>
      <UICommonFooter name="default-footer" />
    </template>
  </BaseLayout>
</template>

<script setup>
import BaseLayout from "./base.vue";

const route = useRoute();

const showBanner = computed(() => route.path === "/citizen");
const showFaqs = computed(() => route.path === "/citizen");
const showUserManual = computed(() => route.path === "/citizen");
</script>
