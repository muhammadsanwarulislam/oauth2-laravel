<template>
  <nav class="bg-gray-800 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <h1
            class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500"
          >
            Oauth2
          </h1>
        </div>
        <div class="flex items-center space-x-4">
          <UICommonButton
            buttonText="Sign In"
            bgColor="#78057c"
            textColor="#ffffff"
            customClass="w-[120px] h-12 px-4 py-2 rounded flex justify-center items-center transition duration-300 border"
            hoverClass="hover:bg-[#6a006e] hover:cursor-pointer"
            @click="redirect"
          />
          <UICommonButton
            buttonText="Sign Up"
            bgColor="#ffffff"
            textColor="#78057c"
            borderColor="#78057c"
            customClass="w-[120px] h-12 px-4 py-2 rounded flex justify-center items-center transition duration-300 border"
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

const { clientId, redirectUri, retrieveClientData, redirectToAuthorization } =
  useOAuth();

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