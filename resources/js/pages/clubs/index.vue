<script setup lang="ts">
import DataTable from '@/components/tables/data-table.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Club, Permissions } from '@/types/entities';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { columns } from './columns';

interface Props {
    clubs: Club[];
    can: Permissions;
}
const props = defineProps<Props>();

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('Club', 2),
        href: '',
    },
];
</script>

<template>
    <Head :title="t('Club', 2)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <DataTable :columns="columns" :data="props.clubs" routeName="club" :permissions="props.can" />
            </div>
        </div>
    </AppLayout>
</template>
