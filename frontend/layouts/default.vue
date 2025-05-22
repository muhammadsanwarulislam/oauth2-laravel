<template>
  <BaseLayout>
    <template #header>
      <UIGuestHeader name="default-header" />
    </template>

    <template v-if="showBanner" #banner>
      <UICommonBanner name="default-banner" />
    </template>

    <template v-if="showFaqs" #faqs>
      <UICommonFaqs name="default-faqs" />
    </template>

    <template v-if="showUserManual" #usermanual>
      <UICommonUserManual name="default-usermanual" />
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
const showFaqs = computed(() => route.path === "/");
const showUserManual = computed(() => route.path === "/");

</script>
