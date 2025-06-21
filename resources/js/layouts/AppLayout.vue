<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Page Header -->
        <header v-if="$slots.header" class="border-b border-border bg-muted/40">
        <div class="container mx-auto py-6 px-4">
            <slot name="header" />
        </div>
        </header>
        <slot />
    </AppLayout>
</template>
