<script setup lang="ts">
import DataTable from '@/components/tables/data-table.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, FilterItems, SharedData } from '@/types';
import { Permissions, Player } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { columns } from './columns';

const page = usePage<SharedData>();

interface Props {
    players: Player[];
    roles: FilterItems[];
    can: Permissions;
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
        href: '',
    },
];

const filter = [
    {
        column: 'role',
        title: t('Role'),
        options: props.roles,
    },
    {
        column: 'active',
        title: t('Active'),
        options: [
            { value: '0', label: t('Inactive') },
            { value: '1', label: t('Active') },
        ],
    },
];

console.log(props.can);
</script>

<template>
    <Head title="Players" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <DataTable
                    v-if="props.roles.length"
                    :columns="columns"
                    :data="props.players || []"
                    :permissions="props.can"
                    routeName="players"
                    :filter="filter"
                />
                <div v-else class="w-full text-center font-semibold">
                    <i18n-t keypath="create roles first" tag="p">
                        <template v-slot:action>
                            <a :href="route('role.index')" class="underline">{{ t('role', 1) }}</a>
                        </template>
                    </i18n-t>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
