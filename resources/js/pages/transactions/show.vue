<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { Transaction } from '@/types/entities';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';

interface Props {
    transaction: Transaction;
}

const page = usePage<SharedData>();

const props = defineProps<Props>();

const { t, d, n } = useI18n();

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
        title: d(props.transaction.date),
        href: '',
    },
];

const formattedType = (type: number) => {
    switch (type) {
        case 1:
            return t('Base Fee');
        case 2:
            return t('Fee');
        case 3:
            return t('Payment');
        case 4:
            return t('Tip');
        case 5:
            return t('Expense');
        default:
            return 'Unknown';
    }
};
</script>

<template>
    <Head title="Show Transaction" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <CardTitle>{{ d(props.transaction.date) }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-[minmax(150px,auto)_1fr] items-center gap-2">
                        <Label class="font-semibold">Type:</Label>
                        <Badge class="w-max">
                            {{ formattedType(props.transaction.type) }}
                        </Badge>
                        <Label class="font-semibold">{{ t('Matchday') }}:</Label>
                        <div>
                            <Link
                                v-if="props.transaction.matchday_id"
                                class="underline"
                                :href="route('matchdays.show', { id: props.transaction.matchday_id })"
                                >{{ props.transaction.matchday ? d(props.transaction.matchday?.date) : '-' }}</Link
                            >
                            <span v-else>-</span>
                        </div>
                        <Label class="font-semibold">{{ t('Player') }}:</Label>
                        <div>
                            <Link
                                v-if="props.transaction.player"
                                class="underline"
                                :href="route('players.show', {id: props.transaction.player_id})"
                                >{{ props.transaction.player.name }}</Link
                            >
                            <span v-else>-</span>
                        </div>
                        <Label class="font-semibold">{{ t('Amount') }}:</Label>
                        <div>{{ n(props.transaction.amount, 'currency') }}</div>
                        <Label class="font-semibold">{{ t('Date') }}:</Label>
                        <div>{{ d(props.transaction.date) }}</div>
                        <Label class="font-semibold">{{ t('Notes') }}:</Label>
                        <div>
                            {{ props.transaction.notes }}
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
