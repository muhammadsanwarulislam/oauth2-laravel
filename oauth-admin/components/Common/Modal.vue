<template>
    <transition name="fade">
      <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-80 transform transition-transform duration-300 ease-in-out scale-100 hover:scale-105">
          <button @click="close" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
          <h3 class="text-lg font-semibold mb-4">{{ title }}</h3>
          <slot></slot>
        </div>
      </div>
    </transition>
  </template>
  
  <script setup>
  const props = defineProps({
    isOpen: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: 'Modal Title',
    }
  });
  
  // Define emit
  const emit = defineEmits();
  
  // Function to close the modal
  const close = () => {
    emit('update:isOpen', false);
  };
  </script>
  
  <style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active in <=2.1.8 */ {
    opacity: 0;
  }
  </style>
  