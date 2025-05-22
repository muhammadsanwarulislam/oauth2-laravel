<template>
  <form
    @submit.prevent="isEditing ? update(clientId) : create()"
    class="bg-white p-6 rounded-lg shadow-md"
  >
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
      <div>
        <UIInputField
          id="name"
          :label="t('name')"
          type="text"
          :placeholder="t('client_name')"
          v-model="form.name"
          required
          :errorMessage="errors.name"
        />
      </div>
      <div>
        <UIInputField
          id="redirect"
          :label="t('redirect')"
          type="text"
          :placeholder="t('redirect')"
          v-model="form.redirect"
          required
          :errorMessage="errors.redirect"
        />
      </div>
    </div>

    <div class="grid grid-cols-1 mt-5">
      <div class="flex flex-nowrap gap-4 justify-end">
        <UIButton
          :buttonText="
            isLoading ? 'Loading...' : isEditing ? 'Update' : 'Create'
          "
          :isLoading="isLoading"
          :isSuccess="isSuccess"
          type="submit"
          color="blue"
          class="flex items-center justify-center"
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
import { useLocale } from "~/composables/useLocale";

const { t } = useLocale();

const props = defineProps({
  isEditing: Boolean,
  clientId: Number,
  isFormView: Boolean,
  clientList: Array,
});

const emit = defineEmits(["update:isFormView"]);

const { createItem, updateItem, message, isValidation, isLoading, isSuccess } = useCommonOperation();

const form = ref({ name: "", redirect: "" });

const validationRules = ref({
  name: { required: true },
  redirect: { required: true },
});


const errors = ref({
  name: "",
  redirect: "",
});

onMounted(() => {
  if (props.clientId) {
    getClientByID(props.clientId);
  }
});

async function getClientByID(id) {
  try {
    const response = await $http(`clients/${id}`, { method: "GET" });
    const data = response.data;
    form.value = {
      name: data.name,
      redirect: data.redirect,
    };
  } catch (error) {
    console.error("Error fetching client:", error);
  }
}

async function create() {
  if (!validateForm()) return; 

  isValidation.value = false;

  try {
    await createItem(
      form.value,
      props.clientList,
      "clients",
      "client",
      validationRules.value
    );

    if (isSuccess.value) {
      resetForm();
    }

    handleApiErrors();
  } catch (error) {
    console.error("Error creating client:", error);
  }
}

async function update(id) {
  try {
    await updateItem(
      id,
      form.value,
      props.clientList,
      "/clients",
      "client",
      validationRules.value
    );

    handleApiErrors();

    resetForm();
  } catch (error) {
    console.error("Error updating client:", error);
  }
}

function declineAction() {
  resetForm();
}

function resetForm() {
  form.value = { name: "", redirect: "" };
  errors.value.redirect = "";
  errors.value.name = "";

  emit("update:isFormView", false);
}

const validateForm = () => {
  errors.value.redirect = '';
  errors.value.name = '';

  let isValid = true;

  if (!form.value.redirect) {
    errors.value.redirect = 'Redirect is required.';
    isValid = false;
  }
  return isValid;
}
const handleApiErrors = () => {
  if (message.value.errors.name) {
    errors.value.name = message.value.errors.name[0];
  }
  if (message.value.errors.redirect) {
    errors.value.redirect = message.value.errors.redirect[0];
  }
};
</script>


<style scoped></style>
