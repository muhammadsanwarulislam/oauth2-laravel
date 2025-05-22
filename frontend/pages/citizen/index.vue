<template>
  <div>
    <pre>{{ userData }}</pre>
  </div>
</template>

<script setup>
useHead({ title: "Citizen" });
import { useUser } from "~/composables/sso/useUser";

const { userData } = useUser();
</script>
