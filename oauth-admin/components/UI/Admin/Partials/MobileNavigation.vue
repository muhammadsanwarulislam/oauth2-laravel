<!-- MobileNavigation.vue -->
<template>
    <aside
      v-if="isSidebarOpen"
      class="fixed inset-0 bg-gray-800 bg-opacity-75 z-50 lg:hidden"
    >
      <div class="w-64 bg-white h-full p-4 shadow-md">
        <button
          class="mb-4 text-gray-500 hover:text-gray-700"
          @click="toggleSidebar"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
        <nav>
          <ul class="space-y-2">
            <li v-for="item in menus" :key="item.component">
              <NuxtLink
                @click.prevent="changeComponent(item.component)"
                class="flex items-center px-4 py-2 rounded text-gray-600 hover:bg-gray-100 hover:text-gray-800"
              >
                <svg
                  v-if="item.icon"
                  xmlns="http://www.w3.org/2000/svg"
                  :class="'h-5 w-5 mr-2'"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path :d="item.icon" />
                </svg>
                {{ item.label }}
              </NuxtLink>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
  </template>
  
  <script setup>
  import { useMenuItems } from '~/composables/useMenuItems';
  
  const props = defineProps({
    isSidebarOpen: {
      type: Boolean,
      required: true,
    },
    toggleSidebar: {
      type: Function,
      required: true,
    },
    changeComponent: {
      type: Function,
      required: true,
    },
  });
  
  const { menus } = useMenuItems();
  </script>
  
  <style scoped></style>
  