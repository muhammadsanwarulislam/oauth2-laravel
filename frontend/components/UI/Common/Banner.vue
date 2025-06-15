<template>
  <section class="bg-gradient-to-r from-gray-900 to-gray-800 py-20 sm:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <!-- Heading -->
      <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500 animate-fade-in">
        Secure Authentication with OAuth2 Laravel
      </h2>

      <!-- Subheading -->
      <p class="mt-6 text-lg sm:text-xl lg:text-2xl max-w-2xl mx-auto text-gray-300 animate-slide-up">
        Power your web applications with fast, reliable, and open-source OAuth2 authentication.
      </p>

      <!-- Call-to-Action Buttons -->
      <div class="mt-10 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 animate-slide-up delay-200">
        <a
          href="https://github.com/muhammadsanwarulislam/oauth2-laravel"
          class="relative px-8 py-3 text-base font-medium text-white bg-gradient-to-r from-purple-600 to-blue-500 rounded-full hover:from-purple-700 hover:to-blue-600 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400" 
        >
          View on GitHub
          <span class="absolute inset-0 rounded-full opacity-0 hover:opacity-20 bg-white transition-opacity duration-300"></span>
        </a>
        <a
          href="https://github.com/muhammadsanwarulislam/oauth2-laravel/blob/main/README.md"
          class="relative px-8 py-3 text-base font-medium text-purple-600 bg-white rounded-full hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 border border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-400"
  
        >
          Get Started
          <span class="absolute inset-0 rounded-full opacity-0 hover:opacity-10 bg-purple-600 transition-opacity duration-300"></span>
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
// No script logic needed for static banner
</script>

<style scoped>
/* Custom animations */
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slide-up {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.animate-fade-in {
  animation: fade-in 1s ease-out forwards;
}
.animate-slide-up {
  animation: slide-up 1s ease-out forwards;
}
.delay-200 {
  animation-delay: 0.2s;
}
</style>