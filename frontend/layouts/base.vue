<template>
    <div class="flex flex-col min-h-screen">
      <!-- Header Slot -->
      <div class= "mb-10 fixed top-0 left-0 right-0 z-[9]">
        <slot name="header"></slot>
      </div>
  
      <!-- Banner Slot (Optional) -->
      <div v-if="showBanner">
        <slot name="banner"></slot>
      </div>
  
      <!-- Main Content -->
      <div class="flex-grow flex container mx-auto my-4 px-4">
        <slot></slot>
      </div>
  
      <!-- FAQs Slot (Optional) -->
      <div v-if="showFeatures">
        <slot name="features"></slot>
      </div>

      <!-- User Manual -->
      <div v-if="showCallToAction">
        <slot name="calltoaction"></slot>
      </div>
  
      <!-- Footer -->
      <div>
        <slot name="footer"></slot>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    showBanner: { type: Boolean, default: true },
    showFeatures: { type: Boolean, default: true },
    showCallToAction: { type: Boolean, default: true },
  });
  </script>
  