<script setup lang="ts" generic="TData, TValue">
import { computed } from 'vue';

import type { Table } from '@tanstack/vue-table';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Permissions } from '@/types/entities';
import { Link } from '@inertiajs/vue3';
import { Cross2Icon } from '@radix-icons/vue';

import { useI18n } from 'vue-i18n';
import type { DataTableFacetedFilter } from './DataTableFacetedFilter.vue';
import FacetedFilter from './DataTableFacetedFilter.vue';

const { t } = useI18n();

interface DataTableToolbarProps {
    table: Table<TData>;
    routeName?: string;
    permissions?: Permissions;
    filter?: DataTableFacetedFilter<TData>[];
}

const props = defineProps<DataTableToolbarProps>();

const isFiltered = computed(() => props.table.getState().columnFilters.length > 0);
</script>

<template>
    <div class="flex items-center justify-between">
        <div class="flex flex-1 items-center space-x-2">
            <template v-if="table.getAllColumns().find((x) => x.id === 'name')">
                <Input
                    :placeholder="t('filter name') + '...'"
                    :model-value="(table.getColumn('name')?.getFilterValue() as string) ?? ''"
                    class="h-8 w-[150px] lg:w-[250px]"
                    @input="table.getColumn('name')?.setFilterValue($event.target.value)"
                />
            </template>
            <template v-for="(filter, index) in props.filter" :key="index">
                <FacetedFilter v-if="filter.column" :column="filter.column" :title="filter.title" :options="filter.options" />
            </template>
            <Button v-if="isFiltered" variant="ghost" class="h-8 px-2 lg:px-3" @click="table.resetColumnFilters()">
                {{ t('Reset') }}
                <Cross2Icon class="ml-2 h-4 w-4" />
            </Button>
        </div>
        <Button variant="default" as-child v-if="props.permissions && props.routeName && props.permissions.create">
            <Link :href="route(props.routeName + '.create')">{{ t('Create') }}</Link>
        </Button>
    </div>
</template>
