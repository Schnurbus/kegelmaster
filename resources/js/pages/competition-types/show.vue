<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { CompetitionType } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const page = usePage<SharedData>();

interface Props {
    competitionType: CompetitionType;
}

const props = defineProps<Props>();

const { t, d } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.currentClubName ? page.props.currentClubName : t('Club'),
        href: '/dashboard',
    },
    {
        title: t('Competition Type', 2),
        href: route('competition-type.index'),
    },
    {
        title: props.competitionType.name,
        href: '',
    },
];
</script>

<template>
    <Head :title="t('Competition Type')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader> </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-[minmax(150px,auto),1fr] items-center gap-2">
                        <Label class="font-semibold">{{ t('Name') }}:</Label>
                        <div>{{ props.competitionType.name }}</div>
                        <Label class="font-semibold">{{ t('Description') }}:</Label>
                        <div>{{ props.competitionType.description }}</div>
                        <Label class="font-semibold">{{ t('Type') }}:</Label>
                        <div>
                            <span v-if="props.competitionType.type === 1">{{ t('Winner') }}/{{ t('Looser') }}</span>
                            <span v-else-if="props.competitionType.type === 2">{{ t('Winner') }}</span>
                            <span v-else-if="props.competitionType.type === 3">{{ t('Looser') }}</span>
                        </div>
                        <Label class="font-semibold">{{ t('Is Sex Specific') }}:</Label>
                        <div>
                            <Check v-if="props.competitionType.is_sex_specific" :size="24" color="green" />
                            <X v-else :size="24" color="red" />
                        </div>
                        <Label class="font-semibold">{{ t('Created at') }}:</Label>
                        <div>{{ d(props.competitionType.created_at) }}</div>
                        <Label class="font-semibold">{{ t('Updated at') }}:</Label>
                        <div>{{ d(props.competitionType.updated_at) }}</div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
