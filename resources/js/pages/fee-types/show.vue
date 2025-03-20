<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { FeeType } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const page = usePage<SharedData>();

interface Props {
    feeType: FeeType;
}

const props = defineProps<Props>();

const { t, d, n } = useI18n();

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
        title: props.feeType.name,
        href: '',
    },
];
</script>

<template>
    <Head title="Fee Type" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader> </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-[minmax(150px,auto)_1fr] items-center gap-2">
                        <Label class="font-semibold">{{ t('Name') }}:</Label>
                        <div>{{ props.feeType.name }}</div>
                        <Label class="font-semibold">{{ t('Description') }}:</Label>
                        <div>{{ props.feeType.description }}</div>
                        <Label class="font-semibold">{{ t('Created at') }}:</Label>
                        <div>{{ d(props.feeType.created_at) }}</div>
                        <Label class="font-semibold">{{ t('Updated at') }}:</Label>
                        <div>{{ d(props.feeType.updated_at) }}</div>
                        <Label class="font-semibold">{{ t('Amount') }}:</Label>
                        <div>{{ n(props.feeType.amount, 'currency') }}</div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
