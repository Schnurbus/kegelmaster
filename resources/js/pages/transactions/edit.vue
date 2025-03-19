<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import { Player, Transaction } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import Form from './form.vue';

const page = usePage<SharedData>();

interface Props {
    players: Player[];
    transaction: Transaction;
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
        href: route('transactions.index'),
    },
    {
        title: t('New Transaction'),
        href: '',
    },
];
</script>

<template>
    <Head :title="t('Edit Transaction')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <Form :players="props.players" :transaction="props.transaction" />
            </div>
        </div>
    </AppLayout>
</template>
