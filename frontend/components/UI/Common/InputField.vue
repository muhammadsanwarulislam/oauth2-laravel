<template>
  <div class="mb-2">
    <label v-if="label" :for="id" class="text-gray-700 text-sm font-medium">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>

    <!-- Text Input -->
    <input v-if="!isSelect && type !== 'radio'" :id="id" :type="type" :placeholder="placeholder" :value="modelValue"
      @input="updateValue($event.target.value)"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-fuchsia-900 focus:border-transparent"
      :required="required" />

    <!-- Select Dropdown -->
    <select v-else-if="isSelect" :id="id" :value="modelValue" @change="updateValue($event.target.value)"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-fuchsia-900 focus:border-transparent"
      :required="required">
      <option selected disabled value="">{{ placeholder }}</option>
      <option v-for="(option, index) in options" :key="index" :value="option.value">
        {{ option.label }}
      </option>
    </select>

    <!-- Radio Button Group -->
    <div v-else-if="type === 'radio'" class="flex space-x-4 mt-2">
      <label v-for="(option, index) in options" :key="index" class="flex items-center">
        <input :id="`${id}-${index}`" type="radio" :name="id" :value="option.value"
          :checked="modelValue === option.value" @change="updateValue(option.value)"
          class="w-4 h-4 text-fuchsia-900 bg-gray-100 border-gray-300 focus:ring-fuchsia-900" />
        <span class="ml-2 text-gray-700">{{ option.label }}</span>
      </label>
    </div>

    <!-- date input -->
    <div v-else-if="type === 'date'" class="flex space-x-4 mt-2">
      <input :id="id" type="date" :value="modelValue" @input="updateValue($event.target.value)"
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-fuchsia-900 focus:border-transparent"
        :required="required" />

    </div>

    <!-- Error Message Display -->
    <p v-if="errorMessage" class="text-red-500 text-sm mt-1">{{ errorMessage }}</p>
  </div>
</template>

<script setup>
const props = defineProps({
  id: { type: String, required: true },
  label: { type: String, required: true },
  type: { type: String, default: "text" },
  placeholder: { type: String, default: "" },
  modelValue: { type: String, default: "" },
  required: { type: Boolean, default: false },
  isSelect: { type: Boolean, default: false },
  options: { type: Array, default: () => [] },
  errorMessage: { type: String, default: "" },
});

const emit = defineEmits(["update:modelValue"]);

const updateValue = (value) => {
  emit("update:modelValue", value);
};
</script>
