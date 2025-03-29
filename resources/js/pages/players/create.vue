<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { Role } from '@/types/entities';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import Form from './form.vue';

const page = usePage<SharedData>();

interface Props {
    roles: Role[];
}

const props = defineProps<Props>();

const { t } = useI18n();

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
        title: t('New Player'),
        href: '',
    },
];
</script>

<template>
    <Head :title="t('New Player')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <Form v-if="props.roles.length > 0" :roles="props.roles" />
                <div v-else class="w-full text-center font-semibold">
                    Please create some <Link :href="route('role.index')" class="underline">roles</Link> first.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
