<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, RoleItem, SharedData } from '@/types';
import { Player } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import Form from './form.vue';

const page = usePage<SharedData>();

interface Props {
    player: Player;
    roles: RoleItem[];
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
        href: '/players',
    },
    {
        title: props.player!.name,
        href: '',
    },
];
</script>

<template>
    <Head :title="t('Edit Player')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <Form :player="props.player" :roles="props.roles" />
            </div>
        </div>
    </AppLayout>
</template>
