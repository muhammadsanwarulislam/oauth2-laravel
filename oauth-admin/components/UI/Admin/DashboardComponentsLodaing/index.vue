<template>
    <main class="flex-grow p-4">
        <ClientOnly>
            <KeepAlive>
                <component :is="getComponent" />
            </KeepAlive>
        </ClientOnly>
    </main>
</template>

<script setup>
useHead({ title: "Dashboard" });

const props = defineProps({
    componentId: {
        type: String,
        default: "Overview",
    }
});


const overview          = resolveComponent("UIAdminRightSideOverview");
const user_management   = resolveComponent("UIAdminRightSideUserManagement");
const client_management = resolveComponent("UIAdminRightSideClientManagement");


const getComponent = computed(() => {
    switch (props.componentId) {
        case "Overview":
            return overview;
        case "UserCreation":
            return user_management;
        case "ClientCreation":
            return client_management;
        default:
            return overview; 
    }
});

</script>

<style scoped></style>