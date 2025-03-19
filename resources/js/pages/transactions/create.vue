<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { Club, Player } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import Form from './form.vue';

const page = usePage<SharedData>();

interface Props {
    players: Player[];
    club: Club;
}

const { t } = useI18n();

const props = defineProps<Props>();

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
    <Head :title="t('New Transaction')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <Form :players="props.players" :club="props.club" :transaction="null" />
            </div>
        </div>
    </AppLayout>
</template>
