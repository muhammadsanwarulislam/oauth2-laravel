<!-- Header Component -->
<template>
  <div>
    <header
      class="bg-white flex items-center justify-between px-6 py-4 rounded-lg"
    >
      <div class="flex items-center space-x-4">
        <button
          class="lg:hidden p-2 bg-gray-200 rounded"
          @click="toggleSidebar"
        >
          <IconsHamburger />
        </button>
        <div class="rounded-lg p-2">
          <h2 class="text-lg font-semibold text-gray-700">Welcome, {{ user.user.first_name }} {{ user.user.last_name }}</h2>
        </div>
      </div>
      <div class="flex items-center space-x-4 relative">
        <!-- Language Switch Option -->
        <UILanguageSwitch />

        <!-- Profile Picture with Modal -->
        <div class="relative">
          <Icon
            name="material-symbols-light:manage-accounts-outline-rounded"
            size="40"
            @click="toggleModal"
          />
        </div>

        <!-- Modal for Profile Actions -->
        <CommonModal
          :isOpen="isModalOpen"
          @update:isOpen="isModalOpen = $event"
          title="Profile Actions"
        >
          <p>Welcome, Admin</p>
          <button
            @click.prevent="logout"
            class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
          >
            Logout
          </button>
        </CommonModal>
      </div>
    </header>
    <UIAdminPartialsMobileNavigation
      :isSidebarOpen="isSidebarOpen"
      :toggleSidebar="toggleSidebar"
      :changeComponent="changeComponent"
    />
  </div>
</template>

<script setup>
const { user } = useAuth();

const props = defineProps({
  changeComponent: {
    type: Function,
    required: true,
  },
});

const isSidebarOpen = ref(false);
const isModalOpen = ref(false);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};


// Function to handle logout
const logout = () => {
  // Implement logout logic here
};

// Function to toggle modal visibility
const toggleModal = () => {
  isModalOpen.value = !isModalOpen.value;
};
</script>

<style scoped></style>
