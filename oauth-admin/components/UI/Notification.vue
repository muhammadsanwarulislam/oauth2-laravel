<template>
  <div class="fixed bottom-4 right-4 space-y-2 z-[9999]">
    <TransitionGroup name="notification">
      <div
        v-for="notification in notifications"
        :key="notification.id"
        :class="[
          'p-4 rounded-lg shadow-lg text-white max-w-xs flex items-start',
          notificationClasses[notification.type]
        ]"
      >
        <span>{{ notification.message }}</span>
        <button 
          @click="remove(notification.id)" 
          class="ml-2 text-white hover:text-gray-200"
        >
          &times;
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useNotification } from '@/composables/useNotification'

const { notifications, remove } = useNotification()

const notificationClasses = {
  success: 'bg-green-500',
  error: 'bg-red-500',
  info: 'bg-blue-500',
  warning: 'bg-yellow-500'
}
</script>

<style scoped>
.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from,
.notification-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>