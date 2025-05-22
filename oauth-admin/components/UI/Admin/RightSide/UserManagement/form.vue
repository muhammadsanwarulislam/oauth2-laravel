<template>
  <form
    @submit.prevent="isEditing ? update(user_id) : create()"
    class="bg-white p-6 rounded-lg shadow-md"
  >
    <div class="grid grid-cols-2 gap-5">
      <div class="">
        <UIInputField
          id="Name"
          label="Name"
          type="text"
          placeholder="User Name"
          v-model="form.name"
          required
        />
      </div>
      <div class="">
        <UIInputField
          id="Email"
          label="Email"
          type="email"
          placeholder="User Email"
          v-model="form.email"
          required
        />
      </div>
      <div class="">
        <UIInputField
          id="Phone"
          label="Phone"
          type="text"
          placeholder="177XXXXXXXX"
          v-model="form.phone"
          required
        />
      </div>
    </div>

    <div class="grid grid-cols-1 mt-5">
      <div class="flex flex-nowrap gap-4 justify-end">
        <UIButton
          :buttonText="isLoading ? '' : 'Create'"
          :isLoading="isLoading"
          :isSuccess="isSuccess"
          type="submit"
          color="blue"
        />
        <UIButton
          :buttonText="'Decline'"
          type="button"
          @click="declineAction"
          color="gray"
        />
      </div>
    </div>
  </form>

</template>

<script setup>
import { useCommonOperation } from "@/composables/useCommonOperation";

const props = defineProps({
  isEditing: Boolean,
  user_id: Number,
  is_form_view: Boolean,
  user_list: Array,
});

const emit = defineEmits(["update:is_form_view"]);

const { createItem, updateItem, message, isValidation, isLoading, isSuccess } = useCommonOperation();

const form = ref({
  name: "",
  email: "",
  phone: "",
});

const validationRules = ref({
  name: { required: true },
  email: { required: true },
  phone: { required: true },
});

onMounted(() => {
  
});

async function create() {
  isValidation.value = false;
  await createItem(
    form.value,
    props.user_list,
    "users",
    "user",
    validationRules.value
  );
  if (isSuccess.value) {
    resetForm();
  }
}

async function update(id) {
  await updateItem(
    id,
    form.value,
    props.user_list,
    "users",
    "user",
    validationRules.value
  );
  resetForm();
}

function declineAction() {
  resetForm();
}

function resetForm() {
  form.value = { name: "", email: "" };
  isValidation.value = false;
  emit("update:is_form_view", false);
}
</script>

<style scoped>
/* Add any additional styles if needed */
</style>
