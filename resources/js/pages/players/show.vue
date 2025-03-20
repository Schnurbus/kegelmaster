<script setup lang="ts">
import DataTable from '@/components/tables/data-table.vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { Player, PlayerStatistics } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { columns as competitionColumns } from './competition_columns';
import { columns as feeColumns } from './fee_columns';
import { columns as transactionColumns } from './transaction_columns';

interface Props {
    player: Player;
    statistics: PlayerStatistics;
}

const page = usePage<SharedData>();

const props = defineProps<Props>();

const { t, n, d } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.currentClubName ? page.props.currentClubName : t('Club'),
        href: '/dashboard',
    },
    {
        title: t('Player', 2),
        href: route('players.index'),
    },
    {
        title: props.player?.name,
        href: '',
    },
];
</script>

<template>
    <Head title="Show Player" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <CardTitle>{{ props.player.name }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-[minmax(150px,auto)_1fr] items-center gap-2">
                        <Label class="font-semibold">{{ t('Name') }}:</Label>
                        <div>{{ props.player.name }}</div>
                        <template v-if="props.player.user">
                            <Label class="font-semibold">{{ t('User') }}:</Label>
                            <div>
                                <a :href="`mailto:${props.player.user.email}`">{{ props.player.user.name }}</a>
                            </div>
                        </template>
                        <Label class="font-semibold">{{ t('Role') }}:</Label>
                        <div>{{ props.player.role.title }}</div>
                        <Label class="font-semibold">{{ t('Created at') }}:</Label>
                        <div>{{ d(props.player.created_at) }}</div>
                        <Label class="font-semibold">{{ t('Balance') }}:</Label>
                        <div>{{ n(props.player.balance, 'currency') }}</div>
                        <Label class="font-semibold">{{ t('Status') }}:</Label>
                        <div>
                            <Badge v-if="props.player.active">{{ t('Active') }}</Badge>
                            <Badge v-else variant="secondary">{{ t('Inactive') }}</Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>{{ t('Statistics') }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <Tabs default-value="fees" class="mt-4 w-full">
                        <TabsList class="grid w-full grid-cols-3">
                            <TabsTrigger value="fees">{{ t('Fee', 2) }}</TabsTrigger>
                            <TabsTrigger value="competitions">{{ t('Competition', 2) }}</TabsTrigger>
                            <TabsTrigger value="transactions">{{ t('Transaction', 2) }}</TabsTrigger>
                        </TabsList>
                        <TabsContent value="fees">
                            <DataTable :columns="feeColumns" :data="props.statistics.fees || []" />
                        </TabsContent>
                        <TabsContent value="competitions">
                            <DataTable :columns="competitionColumns" :data="props.statistics.competitions || []" />
                        </TabsContent>
                        <TabsContent value="transactions">
                            <DataTable :columns="transactionColumns" :data="props.statistics.transactions || []" />
                        </TabsContent>
                    </Tabs>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
