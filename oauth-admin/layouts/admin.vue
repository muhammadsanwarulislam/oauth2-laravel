<template>
    <div class="flex h-screen">
        <!-- Left Sidebar -->
        <UIAdminSidebar :changeComponent="changeComponent" />

        <!-- Main Content Area -->
        <div class="flex flex-col flex-grow">
            <!-- Header -->
            <UIAdminHeader :changeComponent="changeComponent"/>

            <!-- Content Area -->
            <div class="flex flex-grow">
                <!-- Center Content -->
                <UIAdminDashboardComponentsLodaing :componentId="componentId" />
            </div>

            <!-- Footer -->
            <UIAdminPartialsFooter />
        </div>
    </div>
</template>

<script setup>
const componentId = ref("Overview"); 

function changeComponent(newComponent) {
    componentId.value = newComponent; 
}
</script>

<style scoped>

</style>
