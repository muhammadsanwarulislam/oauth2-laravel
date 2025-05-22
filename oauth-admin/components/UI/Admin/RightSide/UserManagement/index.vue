<template>
  <div class="p-4">
    <!-- BreadCrump with search -->
    <div class="flex flex-col md:flex-row md:justify-between p-4">
      <div class="basis-1/4 md:basis-1/3">
        <CommonBreadcrumb :crumbs="breadcrumbs" />
      </div>
      
      <div class="basis-1/8 md:basis-1/3">
        <UIInputField
          id="search"
          type="text"
          v-model="searchQuery"
          :placeholder="t('user_search')"
          @input="getUsers"
        />
      </div>
    </div>

    <!-- Create Permission -->
    <div v-if="is_form_view">
      <UIAdminRightSideUserManagementForm
        :isEditing="isEditing"
        :user_id="user_id"
        :is_form_view="is_form_view"
        :user_list="user_list"
        @update:is_form_view="is_form_view = $event"
      />
    </div>

    <!-- List of user -->
     <div v-else>
       <UIAdminRightSideUserManagementTable
         :userList="user_list"
         :isLoading="spinner"
         :currentPage="current_page"
         :totalPage="total_page"
         @edit="editUser"
         @delete="deleteUser"
       />
   
       <!-- Pagination -->
       <div class="flex justify-between mt-4">
         <button
           @click="prevPage"
           :disabled="current_page === 1"
           class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
         >
           Previous
         </button>
         <span>Page {{ current_page }} of {{ total_page }}</span>
         <button
           @click="nextPage"
           :disabled="current_page >= total_page"
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
  home: "user",
  creation: "user_creation",
};
const { breadcrumbs } = useBreadcrumbs(resetForm, showForm, labels);

const {
  fetchDataWithLimition,
  deleteOperation,
  spinner,
  current_page,
  total_page,
  message,
} = useCommonOperation();

const user_list = ref([]);
const searchQuery = ref("");
const notificationContainer = ref(null);
const user_id = ref(null);

async function getUsers() {
  await fetchDataWithLimition(
    "users", 
    user_list, 
    5, 
    searchQuery.value
  );
}

onMounted(() => {
  getUsers();
});

const is_form_view = ref(false);

function showForm(id = null) {
  is_form_view.value = true;
  if (!isNaN(id)) {
    user_id.value = id;
  }
}

function resetForm() {
  is_form_view.value = false;
}

const prevPage = async () => {
  if (current_page.value > 1) {
    current_page.value--;
    await fetchDataWithLimition(
      "users",
      user_list,
      5,
      searchQuery.value,
      current_page.value
    );
  }
};

const nextPage = async () => {
  if (current_page.value < total_page.value) {
    current_page.value++;
    await fetchDataWithLimition(
      "users",
      user_list,
      5,
      searchQuery.value,
      current_page.value
    );
  }
};

// Edit and delete methods
const editUser = (id) => {
  console.log("Edit user with ID:", id);
};

const deleteUser = async (id) => {
  try {
    await deleteOperation("users", id);

    const index = user_list.value.findIndex((item) => item.id === id);

    if (index !== -1) {
      user_list.value.splice(index, 1);
    }

    notificationContainer.value.addNotification(message, "info");
  } catch (error) {
    notificationContainer.value.addNotification(error.data?.message, "error");
  }
};
</script>

<style scoped></style>
