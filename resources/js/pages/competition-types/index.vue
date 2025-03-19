<script setup lang="ts">
import DataTable from '@/components/tables/data-table.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import type { CompetitionType, Permissions } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { columns } from './columns';

const page = usePage<SharedData>();

interface Props {
    competitionTypes: CompetitionType[];
    can: Permissions;
}

const props = defineProps<Props>();

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.currentClubName ? page.props.currentClubName : t('club'),
        href: '/dashboard',
    },
    {
        title: t('Competition Type', 2),
        href: '',
    },
];
</script>

<template>
    <Head :title="t('Competition Type', 2)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <DataTable :columns="columns" :data="props.competitionTypes || []" :permissions="props.can" routeName="competition-type" />
            </div>
        </div>
    </AppLayout>
</template>
