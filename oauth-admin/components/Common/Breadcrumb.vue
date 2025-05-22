<template>
  <nav class="mb-4">
    <ol class="flex space-x-2 text-gray-600">
      <li v-for="(crumb, index) in crumbs" :key="index" class="flex items-center">

        <template v-if="index > 0">
          <span class="text-gray-400 mx-2">/</span>
        </template>


        <nuxt-link 
          v-if="crumb.action" 
          @click.prevent="crumb.action" 
          class="flex items-center hover:text-blue-600 cursor-pointer" 
        >
          <span v-html="crumb.icon" class="mr-1"></span> 
          {{ crumb.label }}
        </nuxt-link>
        

        <span v-else class="font-medium text-gray-900">
          <span v-html="crumb.icon" class="mr-1"></span> 
          {{ crumb.label }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
const props = defineProps({
  crumbs: {
    type: Array,
    required: true,
  }
});
</script>

<style scoped>
/* Add any additional styles if needed */
</style>
