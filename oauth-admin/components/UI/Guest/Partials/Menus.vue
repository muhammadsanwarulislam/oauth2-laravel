<template>
  <NuxtLink
    v-for="menu in menus"
    :key="menu.id"
    :to="menu.route"
    class="hover:bg-fuchsia-800 hover:text-white rounded-md py-2 px-3 text-sm font-medium text-black"
  >
    {{ menu.label }}
  </NuxtLink>
</template>

<script setup>
import { useMenuItems } from "@/composables/useMenuItems";

const { menus } = useMenuItems();
</script>

<style>
</style>