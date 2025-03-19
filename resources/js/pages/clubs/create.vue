<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import Form from './form.vue';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('Club', 2),
        href: route('club.index'),
    },
    {
        title: t('New Club'),
        href: '',
    },
];
</script>

<template>
    <Head :title="t('New Club')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <div class="mb-4 text-2xl font-semibold">{{ t('Create a club') }}</div>
                <Form />
            </div>
        </div>
    </AppLayout>
</template>
