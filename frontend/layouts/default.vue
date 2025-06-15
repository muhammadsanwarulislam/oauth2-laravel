<template>
  <BaseLayout>
    <template #header>
      <UIGuestHeader name="default-header" />
    </template>

    <template v-if="showBanner" #banner>
      <UICommonBanner name="default-banner" />
    </template>

    <template v-if="showFeatures" #features>
      <UICommonFeatures name="default-features" />
    </template>

    <template v-if="showCallToAction" #calltoaction>
      <UICommonCallToAction name="default-calltoaction" />
    </template>
    
    <slot></slot>

    <template #footer>
      <UICommonFooter name="default-footer" />
    </template>
  </BaseLayout>
</template>

<script setup>
import BaseLayout from "./base.vue";
const route = useRoute();

const showBanner = computed(() => route.path === "/");
const showFeatures = computed(() => route.path === "/");
const showCallToAction = computed(() => route.path === "/");

</script>
