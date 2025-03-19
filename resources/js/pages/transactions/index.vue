<script setup lang="ts">
import DataTable from '@/components/tables/data-table.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import { Permissions, Player, Transaction } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { columns } from './columns';

const page = usePage<SharedData>();

interface Props {
    players: Player[];
    transactions: Transaction[];
    can: Permissions;
}

const props = defineProps<Props>();

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.currentClubName ? page.props.currentClubName : t('Club'),
        href: '/dashboard',
    },
    {
        title: t('Transaction', 2),
        href: '',
    },
];

const filter = [
    {
        column: 'type',
        title: t('Type'),
        options: [
            { value: '1', label: t('Base Fee') },
            { value: '2', label: t('Fee') },
            { value: '3', label: t('Payment') },
            { value: '4', label: t('Tip') },
            { value: '5', label: t('Expense') },
        ],
    },
    {
        column: 'player',
        title: t('Player'),
        options: props.players.map((player) => ({
            value: player.name,
            label: player.name,
        })),
    },
];
</script>

<template>
    <Head :title="t('Transaction', 2)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <DataTable :columns="columns" :data="props.transactions || []" :permissions="props.can" routeName="transactions" :filter="filter" />
            </div>
        </div>
    </AppLayout>
</template>
