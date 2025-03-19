<script setup lang="ts">
import DataTable from '@/components/tables/data-table.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Permissions, Role } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { columns } from './columns';

const page = usePage<SharedData>();

interface Props {
    roles: Role[];
    can: Permissions;
}

const { t } = useI18n();

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.currentClubName ? page.props.currentClubName : t('Club'),
        href: '/dashboard',
    },
    {
        title: t('Role', 2),
        href: '',
    },
];
</script>

<template>
    <Head :title="t('Role', 2)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <DataTable :columns="columns" :data="props.roles || []" :permissions="props.can" routeName="role" />
            </div>
        </div>
    </AppLayout>
</template>
