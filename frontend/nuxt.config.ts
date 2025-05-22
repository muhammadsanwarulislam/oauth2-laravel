// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  ssr: false,
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  vite: {
    plugins: [
      tailwindcss(),
    ],
  },
  modules: ['@nuxt/icon'],
  runtimeConfig: {
    public: {
      apiURL: process.env.NUXT_PUBLIC_API_URL,
      appMode: process.env.NUXT_PUBLIC_APP_MODE,
      oauthClientId: process.env.OAUTH_CLIENT_ID,
      oauthClientCallback: process.env.OAUTH_CLIENT_CALLBACK_URL,
      frontendRedirectURL: process.env.FRONTEND_REDIRECT_URL,
    },
  },
})