export default defineNuxtPlugin({
    name: 'notifications',
    setup(nuxtApp) {
      nuxtApp.provide('notify', useNotification().add)
    }
  })