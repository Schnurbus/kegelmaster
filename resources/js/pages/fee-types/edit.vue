<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import { FeeType } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import Form from './form.vue';

const page = usePage<SharedData>();

interface Props {
    feeType: FeeType;
}

const props = defineProps<Props>();

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.currentClubName ? page.props.currentClubName : t('Club'),
        href: '/dashboard',
    },
    {
        title: t('Fee Type', 2),
        href: route('fee-type.index'),
    },
    {
        title: props.feeType.name,
        href: '',
    },
];
</script>

<template>
    <Head :title="t('Edit Fee Type')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <Form :feeType="props.feeType" />
            </div>
        </div>
    </AppLayout>
</template>
