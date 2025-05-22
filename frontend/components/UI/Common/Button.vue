<template>
  <button
    :class="[
      customClass,
      hoverClass,
      animationClass,
    ]"
    :style="{
      backgroundColor: bgColor,
      color: textColor,
      borderColor: borderColor,
    }"
    :type="type"
    :disabled="disabled"
    @click="onClick"
  >

    <slot>
      {{ buttonText }}
    </slot>
  </button>
</template>

<script setup>
defineProps({
  buttonText: { type: String, default: '' }, 
  bgColor: { type: String, default: '' }, 
  textColor: { type: String, default: '' }, 
  borderColor: { type: String, default: '' }, 
  type: { type: String, default: 'button' }, 
  disabled: { type: Boolean, default: false }, 
  customClass: { type: String, default: '' }, 
  hoverClass: { type: String, default: '' }, 
  animationClass: { type: String, default: '' }, 
  onClick: { type: Function, default: () => {} }, 
});
</script>