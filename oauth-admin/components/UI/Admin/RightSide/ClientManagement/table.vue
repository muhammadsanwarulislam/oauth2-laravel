<template>
  <div class="relative overflow-x-auto sm:rounded-lg mb-4">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr class="bg-white">
          <th scope="col" class="px-2 py-4 text-center">#</th>
          <th scope="col" class="px-4 py-4">{{ t('name') }}</th>
          <th scope="col" class="px-4 py-4">{{ t('secret') }}</th>
          <th scope="col" class="px-4 py-4 text-center">{{ t('action') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="isLoading"
          class="bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 h-80">
          <td colspan="5">
            <div class="flex justify-center">
              <div class="loader-three-dots"></div>
            </div>
          </td>
        </tr>
        <tr v-else-if="!isLoading && clientList.length === 0"
          class="bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 h-80">
          <td colspan="5">
            <div class="flex justify-center">
              <p class="text-gray-500 dark:text-gray-400">No data found</p>
            </div>
          </td>
        </tr>
        <tr v-else v-for="(item, index) in clientList" :key="index"
          class="odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
          <td class="px-2 py-4 text-center">
            {{ item.id }}
          </td>
          <th scope="row" class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            {{ item.name }}
          </th>
          <th scope="row" class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            {{ item.secret }}
          </th>
          <td class="px-4 py-4 text-center text-gray-900">
            <button class="m-1" size="xs" gradient="gray" @click="editClient(item.id)">
              <Icon name="mynaui:edit-one" size="25" />
            </button>
            <button class="m-1" size="xs" gradient="gray" @click="deleteClient(item.id)">
              <Icon name="mdi:delete-off" size="25" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { useLocale } from "~/composables/useLocale";

const { t } = useLocale();

const props = defineProps({
  clientList: Array,
  isLoading: Boolean,
  limit: Number,
  currentPage: Number
});

const emit = defineEmits(['edit', 'delete']); 

const editClient = (id) => {
  emit('edit', id); 
};

const deleteClient = (id) => {
  emit('delete', id); 
};
</script>
