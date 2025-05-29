<template>
  <div class="flex items-center space-x-2 cursor-pointer select-none" @click="toggleLanguage">
    <div class="flex border border-gray-400 rounded-full overflow-hidden">
      <span class="px-4 py-1 text-sm font-medium transition-colors duration-300"
        :class="isEnglish ? 'bg-blue-600 text-white' : 'bg-white text-gray-600'">
        EN
      </span>
      <span class="px-4 py-1 text-sm font-medium transition-colors duration-300" :class="!isEnglish ? 'bg-blue-600 text-white' : 'bg-white text-gray-600'
        ">
        BN
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useLocale } from "~/composables/useLocale";

const { locale, changeLocale } = useLocale();

const isEnglish = computed(() => locale.value === "en");

const toggleLanguage = () => {
  const newLocale = isEnglish.value ? "bn" : "en";
  changeLocale(newLocale);
};
</script>
