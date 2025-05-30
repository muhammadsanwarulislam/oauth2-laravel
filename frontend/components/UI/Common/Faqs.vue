<template>
  <div class="w-full max-w-screen-lg mx-auto py-10 px-4">
    <!-- FAQ Title Section -->
    <div class="text-center mb-8">
      <h2 class="text-white text-2xl font-medium">
        Frequently Asked Questions
      </h2>
      <div class="w-full max-w-[383px] mx-auto mt-3 border-t-2 border-[#531f54]"></div>
    </div>

    <!-- FAQ Items -->
    <div v-for="(faq, index) in faqs" :key="index" class="mb-4">
      <div @click="toggleFAQ(index)"
        class="bg-white rounded-lg border border-[#78057c] p-5 cursor-pointer transition-all duration-300 hover:shadow-lg"
        :class="{ 'bg-[#f9f9f9]': !faq.isOpen, 'shadow-md': faq.isOpen }">
        <div class="flex justify-between items-center">
          <p class="text-black text-base font-semibold">
            {{ faq.question }}
          </p>
          <div class="ml-4 transform transition-transform duration-300" :class="{ 'rotate-180': faq.isOpen }">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M4.5 6.75L9 11.25L13.5 6.75" stroke="black" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
            </svg>
          </div>
        </div>
        <div v-if="faq.isOpen" class="mt-4">
          <p class="text-gray-600 text-sm font-normal">
            {{ faq.answer }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// FAQ Data
const faqs = ref([
  {
    question: "What is OAuth2, and how does it work with this application?",
    answer: "OAuth2 is an authorization framework that allows users to securely log in to our application using their existing credentials from supported providers (e.g., Google, Facebook). In this application, Laravel handles the server-side OAuth2 authentication, while Nuxt.js manages the front-end user experience, ensuring a seamless and secure login process.",
    isOpen: false,
  },
  {
    question: "Do I need to install any software to use this application?",
    answer: "No, you don’t need to install any software. This is a web-based application built with Laravel and Nuxt.js, accessible through any modern web browser on your computer or mobile device.",
    isOpen: false,
  },
  {
    question: "Is there an opportunity to apply for the service on behalf of someone else?",
    answer: "Yes, you can apply for services on behalf of someone else, provided you have the necessary authorization and documentation.",
    isOpen: false,
  },
]);

// Function to toggle FAQ open/close
const toggleFAQ = (index) => {
  faqs.value[index].isOpen = !faqs.value[index].isOpen;
};
</script>

<style scoped></style>