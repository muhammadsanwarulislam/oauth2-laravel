<template>
  <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="loader"></div>
  </div>
</template>

<script setup>
const visible = ref(false);

const show = () => {
  visible.value = true;
};

const hide = () => {
  visible.value = false;
};

defineExpose({ show, hide });
</script>

<style>
.loader {
  border: 6px solid rgba(255, 255, 255, 0.2); 
  border-top-color: #78057c; 
  border-radius: 50%; 
  width: 64px; 
  height: 64px;
  animation: spin 1s linear infinite; 
  box-shadow: 0 0 20px rgba(255, 255, 255, 0.3); 
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>