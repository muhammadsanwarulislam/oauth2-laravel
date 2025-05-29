<!-- Sidebar Component -->
<template>
    <aside class="w-64 bg-white shadow-md flex-shrink-0 hidden lg:block">
        <div class="p-4">
            <div class="flex justify-center">
                <Icon name="ic:twotone-dashboard" size="30" />
            </div>
            <div class="flex justify-center">
                <h1 class="text-sm font-bold text-gray-700">Admin Dashboard</h1>
            </div>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li v-for="item in menus" :key="item.component">
                    <NuxtLink
                        @click.prevent="changeComponent(item.component)"
                        class="flex items-center px-4 py-2 rounded text-gray-600 hover:bg-gray-100 hover:text-gray-800"
                    >
                        <svg v-if="item.icon" xmlns="http://www.w3.org/2000/svg" :class="'h-5 w-5 mr-2'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path :d="item.icon" />
                        </svg>
                        {{ item.label }}
                    </NuxtLink>
                </li>
            </ul>
        </nav>
    </aside>
</template>

<script setup>
import { useMenuItems } from "@/composables/useMenuItems";
const props = defineProps({
    changeComponent: {
        type: Function,
        required: true,
    }
});

const { menus } = useMenuItems();
</script>

<style scoped></style>

