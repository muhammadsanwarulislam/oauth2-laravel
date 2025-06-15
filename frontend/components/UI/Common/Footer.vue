<template>
  <footer class="bg-gradient-to-r from-gray-900 to-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Project Branding -->
      <div class="flex justify-center mb-8">
        <a href="/" class="flex items-center space-x-2">
          <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 00-10 10c0 4.42 2.87 8.17 6.84 9.5.5.09.66-.22.66-.49v-1.7c-2.78.6-3.36-1.34-3.36-1.34-.46-1.16-1.12-1.47-1.12-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.89 1.52 2.34 1.08 2.91.83.09-.65.35-1.08.63-1.33-2.22-.25-4.55-1.11-4.55-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.26.1-2.63 0 0 .84-.27 2.75 1.02A9.58 9.58 0 0112 6.8c.85 0 1.71.11 2.51.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.38.1 2.63.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.16.58.67.49A10.01 10.01 0 0022 12c0-5.52-4.48-10-10-10z"></path>
          </svg>
          <span class="text-2xl font-bold text-white">OAuth2 Laravel</span>
        </a>
      </div>

      <!-- Community Links -->
      <div class="flex justify-center space-x-6 mb-8">
        <a href="https://github.com/muhammadsanwarulislam/oauth2-laravel" class="text-gray-300 hover:text-blue-400 transition-colors" aria-label="GitHub Repository">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 4.42 2.87 8.17 6.84 9.5.5.09.66-.22.66-.49v-1.7c-2.78.6-3.36-1.34-3.36-1.34-.46-1.16-1.12-1.47-1.12-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.89 1.52 2.34 1.08 2.91.83.09-.65.35-1.08.63-1.33-2.22-.25-4.55-1.11-4.55-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.26.1-2.63 0 0 .84-.27 2.75 1.02A9.58 9.58 0 0112 6.8c.85 0 1.71.11 2.51.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.38.1 2.63.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.16.58.67.49A10.01 10.01 0 0022 12c0-5.52-4.48-10-10-10z"/></svg>
        </a>
      </div>

      <!-- GitHub Star Button -->
      <div class="text-center mb-8">
        <a href="https://github.com/muhammadsanwarulislam/oauth2-laravel" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.247a10 10 0 00-3.162 19.487c.5.088.687-.212.687-.475 0-.237-.012-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.247C22 6.706 17.522 2.247 12 2.247z"/></svg>
          Star us on GitHub
        </a>
      </div>

      <!-- Footer Text -->
      <div class="text-center text-gray-400">
        <p class="text-sm">
          © {{ new Date().getFullYear() }} <a href="https://github.com/muhammadsanwarulislam" class="text-blue-400 hover:underline">Muhammad Sanwarul Islam</a>. Licensed under <a href="https://opensource.org/licenses/MIT" class="text-blue-400 hover:underline">MIT License</a>.
        </p>
        <p class="text-sm mt-2">
          Built with <a href="https://laravel.com" class="text-blue-400 hover:underline">Laravel</a>, <a href="https://nuxtjs.org" class="text-blue-400 hover:underline">Nuxt.js</a>, and <span class="text-red-400">♥</span> for secure OAuth2 authentication.
        </p>
        <p class="text-sm mt-2">
          Contribute to <a href="https://github.com/muhammadsanwarulislam/oauth2-laravel/issues" class="text-blue-400 hover:underline">OAuth2 Laravel</a> or join our <a href="https://discord.gg/your-discord" class="text-blue-400 hover:underline">community</a>.
        </p>
      </div>

      <!-- Dynamic Contributor Showcase -->
      <div v-if="contributors.length" class="mt-8 text-center">
        <p class="text-sm text-gray-400">Thanks to our amazing contributors:</p>
        <div class="flex justify-center flex-wrap gap-2 mt-2">
          <a v-for="contributor in contributors" :key="contributor.login" :href="contributor.url" class="group relative">
            <img :src="contributor.avatar" alt="Contributor avatar" class="w-10 h-10 rounded-full border-2 border-gray-700 group-hover:border-blue-400 transition-colors">
            <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded px-2 py-1 -top-8 left-1/2 transform -translate-x-1/2">{{ contributor.login }}</span>
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Fetch contributors from GitHub API
const contributors = ref([])

onMounted(async () => {
  try {
    const response = await fetch('https://api.github.com/repos/muhammadsanwarulislam/oauth2-laravel/contributors')
    const data = await response.json()
    contributors.value = data.slice(0, 5).map(contributor => ({
      login: contributor.login,
      url: contributor.html_url,
      avatar: contributor.avatar_url
    }))
  } catch (error) {
    console.error('Error fetching contributors:', error)
  }
})
</script>

<style scoped>
/* Subtle hover animation for contributor avatars */
img {
  transition: transform 0.3s ease;
}
img:hover {
  transform: scale(1.1);
}
</style>