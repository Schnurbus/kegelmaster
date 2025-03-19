<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/lib/utils';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { Role } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const page = usePage<SharedData>();

interface Props {
    role: Role;
}

const props = defineProps<Props>();

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.currentClubName ? page.props.currentClubName : t('Club'),
        href: '/dashboard',
    },
    {
        title: t('Role', 2),
        href: route('role.index'),
    },
    {
        title: props.role.name,
        href: '',
    },
];

const capitalize = (value: string) => {
    return value
        .split(' ')
        .map((word: string) => word[0].toUpperCase() + word.slice(1))
        .join(' ');
};
</script>

<template>
    <Head :title="t('New Role')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader></CardHeader>
                <CardContent>
                    <div class="grid grid-cols-[minmax(150px,auto),1fr] items-center gap-2">
                        <Label class="font-semibold">{{ t('Name') }}:</Label>
                        <div>{{ props.role.name }}</div>
                        <Label class="font-semibold">{{ t('Created at') }}:</Label>
                        <div>{{ formatDate(props.role.created_at) }}</div>
                        <Label class="font-semibold">{{ t('Base Fee') }}:</Label>
                        <div>
                            <Badge v-if="props.role.is_base_fee_active">{{ t('Active') }}</Badge>
                            <Badge v-else variant="secondary">{{ t('Inactive') }}</Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card v-for="(actions, entity) in props.role.permissions" :key="entity">
                <CardHeader>
                    <CardTitle class="text-sm">{{ t(capitalize(entity), 2) }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-5">
                        <div class="flex items-center space-x-2" v-for="(value, action) in actions" :key="action">
                            <Check v-if="value" :size="24" color="green" />
                            <X v-else :size="24" color="red" />
                            <Label>{{ t(capitalize(action)) }}</Label>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
