<script setup lang="ts" generic="TData extends TDataWithPermissions, TValue">
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { valueUpdater } from '@/lib/utils';
import { SharedData } from '@/types';
import { Permissions, TDataWithPermissions } from '@/types/entities';
import { router, usePage } from '@inertiajs/vue3';
import type { ColumnDef, ColumnFiltersState, SortingState, Updater, VisibilityState } from '@tanstack/vue-table';
import {
    FlexRender,
    getCoreRowModel,
    getFacetedRowModel,
    getFacetedUniqueValues,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { onMounted, Ref, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';
import type { DataTableFacetedFilter } from './DataTableFacetedFilter.vue';
import DataTablePagination from './DataTablePagination.vue';
import DataTableToolbar from './DataTableToolbar.vue';

interface Filter {
    column: string;
    title: string;
    options: { label: string; value: string }[];
}

interface DataTableProps {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    routeName?: string;
    permissions?: Permissions;
    filter?: Filter[];
}

const page = usePage<SharedData>();
const props = defineProps<DataTableProps>();

const { t } = useI18n();

const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});

const identifier = page.url.slice(1) + '-table-filters-' + page.props.currentClubId;

const updateColumnFilter = <T extends Updater<any>>(updaterOrValue: T, ref: Ref) => {
    valueUpdater(updaterOrValue, ref);
    localStorage.setItem(identifier, JSON.stringify(table.getState().columnFilters));
};

function loadFiltersFromStorage() {
    const stored = localStorage.getItem(identifier);
    if (stored) {
        try {
            const filters = JSON.parse(stored) as Array<{ id: string; value: string[] }>;
            if (filters) {
                filters.forEach((element) => {
                    table.getColumn(element.id)?.setFilterValue(element.value);
                });
            }
        } catch (e) {
            console.error('Fehler beim Parsen der gespeicherten Filter:', e);
        }
    }
}

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
    },
    enableRowSelection: true,
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: (updaterOrValue) => updateColumnFilter(updaterOrValue, columnFilters),
    onRowSelectionChange: (updaterOrValue) => valueUpdater(updaterOrValue, rowSelection),
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
});

const facetedfilter: DataTableFacetedFilter<TData>[] = props.filter
    ? props.filter
          .map((filter) => {
              if (table.getColumn(filter.column)) {
                  return {
                      column: table.getColumn(filter.column),
                      title: filter.title,
                      options: filter.options,
                  };
              }
          })
          .filter((f) => f !== undefined)
    : [];

onMounted(() => {
    loadFiltersFromStorage();
});

const showEntry = (id: number) => {
    if (!props.routeName) {
        return '';
    }
    router.visit(route(props.routeName + '.show', id as unknown as RouteParams<string>));
};
</script>

<template>
    <div class="space-y-4">
        <DataTableToolbar :table="table" :routeName="props.routeName" :permissions="props.permissions" :filter="facetedfilter" />
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() && 'selected'">
                            <template v-if="row.original.can && row.original.can.view">
                                <TableCell
                                    v-for="(cell, index) in row.getVisibleCells()"
                                    class="cursor-pointer"
                                    :key="cell.id"
                                    @click="index !== row.getVisibleCells().length - 1 ? showEntry(row.original.id) : null"
                                >
                                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </TableCell>
                            </template>
                            <TableCell v-else v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                            </TableCell>
                        </TableRow>
                    </template>
                    <TableRow v-else>
                        <TableCell :colspan="columns.length" class="h-24 text-center">{{ t('No results') }}.</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
        <DataTablePagination :table="table" />
    </div>
</template>
