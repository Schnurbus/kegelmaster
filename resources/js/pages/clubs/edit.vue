<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Club } from '@/types/entities';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import Form from './form.vue';

interface Props {
    club: Club;
}

const props = defineProps<Props>();

const { t } = useI18n();

const title = computed(() => props.club.name);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('Club', 2),
        href: '/club',
    },
    {
        title: title.value,
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
                    <Form :club="props.club" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
