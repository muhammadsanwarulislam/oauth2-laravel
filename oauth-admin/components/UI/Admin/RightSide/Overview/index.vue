<template>
    <div>
        <h5>{{ t('welcome') }}</h5>
    </div>
</template>

<script setup>
import { useLocale } from '~/composables/useLocale';

const { t } = useLocale();
</script>
