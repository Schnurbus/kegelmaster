<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import type { Club } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import Form from './form.vue';

const page = usePage<SharedData>();

interface Props {
    club: Club;
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
        title: t('New Fee Type'),
        href: '',
    },
];
</script>

<template>
    <Head :title="t('New Fee Type')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <Form :club="props.club" />
            </div>
        </div>
    </AppLayout>
</template>
