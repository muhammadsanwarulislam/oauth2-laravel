<template>
  <nav class="bg-gray-900 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <NuxtLink to="/" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 00-10 10c0 4.42 2.87 8.17 6.84 9.5.5.09.66-.22.66-.49v-1.7c-2.78.6-3.36-1.34-3.36-1.34-.46-1.16-1.12-1.47-1.12-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.89 1.52 2.34 1.08 2.91.83.09-.65.35-1.08.63-1.33-2.22-.25-4.55-1.11-4.55-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.26.1-2.63 0 0 .84-.27 2.75 1.02A9.58 9.58 0 0112 6.8c.85 0 1.71.11 2.51.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.38.1 2.63.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.16.58.67.49A10.01 10.01 0 0022 12c0-5.52-4.48-10-10-10z"></path>
            </svg>
            <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
              OAuth2 Laravel
            </h1>
          </NuxtLink>
        </div>
        <!-- Buttons -->
        <div class="flex items-center space-x-4">
          <UICommonButton
            buttonText="Sign In"
            bgColor="#78057c"
            textColor="#ffffff"
            customClass="relative px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-blue-500 rounded-full hover:from-purple-700 hover:to-blue-600 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
            hoverClass="hover:bg-[#6a006e] hover:cursor-pointer"
            @click="redirect"
          />
          <UICommonButton
            buttonText="Sign Up"
            bgColor="#ffffff"
            textColor="#78057c"
            borderColor="#78057c"
            customClass="relative px-6 py-2 text-sm font-medium text-purple-600 bg-white rounded-full hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 border border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2"
            hoverClass="hover:bg-[#f5e6f7] hover:cursor-pointer"
            @click="signUp"
          />
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useOAuth } from "~/composables/sso/useOAuth";

const { clientId, redirectUri, retrieveClientData } = useOAuth();

onMounted(() => {
  retrieveClientData();
});

const FRONTEND_REDIRECT_URL = useRuntimeConfig().public.frontendRedirectURL;
const BASE_URL = new URL(FRONTEND_REDIRECT_URL).origin;

const redirect = () => {
  const redirectUriValue = encodeURIComponent(redirectUri.value);
  const authorizationUrl = `${BASE_URL}/authorization?client_id=${clientId.value}&redirect=${redirectUriValue}`;
  window.location.href = authorizationUrl;
};

const signUp = () => {
  window.location.href = `${BASE_URL}/signup?client_id=${clientId.value}&redirect=${redirectUri.value}`;
};
</script>