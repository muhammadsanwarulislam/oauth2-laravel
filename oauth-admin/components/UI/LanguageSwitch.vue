<template>
  <div 
    class="flex justify-center items-center space-x-2 cursor-pointer"
    @click="toggleLanguage"
  >
    <span class="hover:bg-fuchsia-800 hover:text-white rounded-md py-2 px-3 text-sm font-medium text-black">{{ isEnglish ? "ENG" : "BN" }}</span>
    <IconsLanguage />
  </div>
</template>

<script setup>
import { useLocale } from '~/composables/useLocale';

const { locale, changeLocale } = useLocale();

const isEnglish = computed(() => locale.value === 'en');

const toggleLanguage = () => {
  const newLocale = isEnglish.value ? 'bn' : 'en';
  changeLocale(newLocale);
}; 
</script>