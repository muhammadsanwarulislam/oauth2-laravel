<template>
  <div class="relative flex justify-center items-center space-x-2 cursor-pointer">
    <button
      class="hover:bg-fuchsia-800 hover:text-white rounded-md py-2 px-3 text-sm font-medium text-black flex items-center"
      @click="toggleDropdown">
      {{ isEnglish ? "ENG" : "বাংলা" }}
      <IconsLanguage class="ms-2" />
    </button>
    <div v-if="isDropdownOpen" class="absolute top-10 bg-white shadow-lg rounded-md py-2 z-10">
      <ul>
        <li class="px-4 py-2 hover:bg-fuchsia-800 hover:text-white cursor-pointer" @click="selectLanguage('en')">
          ENG
        </li>
        <li class="px-4 py-2 hover:bg-fuchsia-800 hover:text-white cursor-pointer" @click="selectLanguage('bn')">
          বাংলা
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useLocale } from '~/composables/localization/useLocale';

const { locale, changeLocale } = useLocale();
const isEnglish = computed(() => locale.value === 'en');
const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const selectLanguage = (lang) => {
  changeLocale(lang);
  isDropdownOpen.value = false; // Close the dropdown after selection
};
</script>
