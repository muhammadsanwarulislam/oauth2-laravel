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
      frontendURL: process.env.FRONTEND_URL,
    },
  },
})