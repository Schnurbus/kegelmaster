<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Club } from '@/types/entities';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

interface Props {
    club: Club;
}

const props = defineProps<Props>();

const { t, d, n } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('Club', 2),
        href: '/club',
    },
    {
        title: props.club.name,
        href: '',
    },
];
</script>

<template>
    <Head :title="props.club.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <CardTitle>{{ props.club.name }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-[minmax(150px,auto)_1fr] items-center gap-2">
                        <Label class="font-semibold">{{ t('Name') }}:</Label>
                        <div>{{ props.club.name }}</div>
                        <Label class="font-semibold">{{ t('Owner') }}:</Label>
                        <div>{{ props.club.owner.name }}</div>
                        <Label class="font-semibold">{{ t('Balance') }}:</Label>
                        <div>{{ n(props.club.balance, 'currency') }}</div>
                        <Label class="font-semibold">{{ t('Base Fee') }}:</Label>
                        <div>{{ n(props.club.base_fee, 'currency') }}</div>
                        <Label class="font-semibold">{{ t('Player', 2) }}:</Label>
                        <div>{{ n(props.club.players_count || 0) }}</div>
                        <Label class="font-semibold">{{ t('Created at') }}:</Label>
                        <div>{{ d(props.club.created_at) }}</div>
                        <Label class="font-semibold">{{ t('Updated at') }}:</Label>
                        <div>{{ d(props.club.updated_at!) }}</div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
