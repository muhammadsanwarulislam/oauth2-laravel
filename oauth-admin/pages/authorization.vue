<template>
  <div class="bg-gray-100">
    <!-- Always show loading spinner -->
    <UILoading ref="loader" />
    
    <!-- Only show content if we're not in any redirect process -->
    <div v-if="!isProcessing">
      <div class="flex flex-col items-center mb-2">
        <IconsLogin />
        <h1 class="text-2xl font-semibold text-gray-900 mt-2">
          Concent Authorization
        </h1>
      </div>

      <div class="w-full max-w-[400px] mx-auto">
        <div class="bg-white rounded-lg shadow-md flex flex-col p-8">
          <p v-if="error" class="text-red-500">{{ error }}</p>
          <div class="mb-4">
            <p class="text-gray-800 text-sm">
              <span class="font-semibold">Client ID:</span> {{ clientId }}
            </p>
          </div>
          <div class="mb-4">
            <p class="text-gray-800 text-sm">
              <span class="font-semibold">Redirect URI:</span> {{ redirectUri }}
            </p>
          </div>
          <div class="flex items-center justify-between mb-4">
            <p class="text-gray-800 text-sm">
              <span class="font-semibold">Scope:</span> read write
            </p>
            <p class="text-gray-800 text-sm">
              <span class="font-semibold">Expires in:</span> 1 hour
            </p>
          </div>
          <div class="flex flex-col space-y-4">
            <UIButton
              :buttonText="'Approve'"
              :isLoading="isLoading"
              :isSuccess="isSuccess"
              type="submit"
              @click="handleApproval"
            />
            <UIButton 
              :buttonText="'Deny'" 
              type="button" 
              @click="handleDeny" 
              :color="'red'" 
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthorization } from "~/composables/useAuthorization";

useHead({ title: "Authorization" });

const loader = ref(null);
const isProcessing = ref(true); 

const {
  clientId,
  redirectUri,
  isLoading,
  isSuccess,
  error, 
  initialize,
  checkUserAuthentication,
  approveAuthorization,
  deny,
} = useAuthorization();

onMounted(async () => {
  try {
    loader.value?.show();
    initialize();

    const userIsLoggedIn = await checkUserAuthentication();

    if (!userIsLoggedIn) {
      isProcessing.value = true;
      window.location.href = `/signin?redirect=${encodeURIComponent(redirectUri.value)}&client_id=${clientId.value}`;
      return;
    }

    isProcessing.value = true;
    await approveAuthorization();
    
  } catch (err) {
    error.value = 'Failed to process authorization';
    isProcessing.value = false; 
  } finally {
    if (!isProcessing.value) {
      loader.value?.hide();
    }
  }
});

const handleApproval = async () => {
  try {
    isProcessing.value = true;
    loader.value?.show();
    await approveAuthorization();
  } catch (err) {
    error.value = 'Failed to process approval';
    isProcessing.value = false;
    loader.value?.hide();
  }
};

const handleDeny = async () => {
  try {
    isProcessing.value = true;
    loader.value?.show();
    deny();
  } catch (err) {
    error.value = 'Failed to process denial';
    isProcessing.value = false;
    loader.value?.hide();
  }
};
</script>