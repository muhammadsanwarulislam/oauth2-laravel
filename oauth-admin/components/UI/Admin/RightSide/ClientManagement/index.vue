<template>
  <div class="p-4">

    <!-- BreadCrump with search -->
    <div class="flex flex-col md:flex-row md:justify-between p-4">
      <div class="basis-1/4 md:basis-1/3">
        <CommonBreadcrumb :crumbs="breadcrumbs" />
      </div>

      <div v-if="!isFormView" class="basis-1/8 md:basis-1/3">
        <UIInputField
          type="text"
          v-model="searchQuery"
          :placeholder="t('client_search')"
          @input="getClients"
        />
      </div>
    </div>

    <!-- Create Client -->
    <div v-if="isFormView">
      <UIAdminRightSideClientManagementForm
        :isEditing="isEditing"
        :clientId="clientId"
        :isFormView="isFormView"
        :clientList="clientList"
        @update:isFormView="isFormView = $event"
      />
    </div>

    <!-- List of clients -->
    <div v-else>
      <UIAdminRightSideClientManagementTable
        :clientList="clientList"
        :isLoading="spinner"
        :currentPage="currentPage"
        :totalPage="totalPage"
        @edit="editClient"
        @delete="deleteClient"
      />
      
      <!-- Pagination -->
      <div class="flex justify-between mt-4">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
        >
          Previous
        </button>
        <span>Page {{ currentPage }} of {{ totalPage }}</span>
        <button
          @click="nextPage"
          :disabled="currentPage >= totalPage"
          class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Notification -->
    <UINotificationContainer ref="notificationContainer" />
  </div>
</template>

<script setup>
import { useCommonOperation } from "@/composables/useCommonOperation";
import { useBreadcrumbs } from "@/composables/useBreadcrumbs";
import { useLocale } from "~/composables/useLocale";

const { t } = useLocale();

const labels = {
  home: "client",
  creation: "client_creation",
};
const { breadcrumbs } = useBreadcrumbs(resetForm, showForm, labels);

const {
  fetchDataWithLimition,
  deleteOperation,
  spinner,
  currentPage,
  totalPage,
  message,
} = useCommonOperation();

const clientList = ref([]);
const searchQuery = ref("");
const notificationContainer = ref(null);
const clientId = ref(null);
const isEditing = computed(() => !!clientId.value);

async function getClients() {
  await fetchDataWithLimition(
    "clients",
    clientList,
    5,
    searchQuery.value
  );
}

onMounted(() => {
  getClients();
});

const isFormView = ref(false);

function showForm(id = null) {
  isFormView.value = true;
  if (!isNaN(id)) {
    clientId.value = id;
  }
}

function resetForm() {
  isFormView.value = false;
}

const prevPage = async () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    await fetchDataWithLimition(
      "clients",
      clientList,
      5,
      searchQuery.value,
      currentPage.value
    );
  }
};

const nextPage = async () => {
  if (currentPage.value < totalPage.value) {
    currentPage.value++;
    await fetchDataWithLimition(
      "clients",
      clientList,
      5,
      searchQuery.value,
      currentPage.value
    );
  }
};


const editClient = (id) => {
  console.log("Edit user with ID:", id);
};

const deleteClient = async (id) => {
  try {
    await deleteOperation("clients", id);

    const index = clientList.value.findIndex((item) => item.id === id);

    if (index !== -1) {
      clientList.value.splice(index, 1);
    }

    notificationContainer.value.addNotification(message, "info");
  } catch (error) {
    notificationContainer.value.addNotification(error.data?.message, "error");
  }
};
</script>

<style scoped></style>
