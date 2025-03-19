import DropdownAction from '@/components/tables/data-table-dropdown.vue';
import { Button } from '@/components/ui/button';
import i18n from '@/i18n';
import type { FeeType } from '@/types/entities';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h } from 'vue';

export const columns: ColumnDef<FeeType>[] = [
    {
        accessorKey: 'id',
        header: () => h('div', 'Id'),
        cell: ({ row }) => row.getValue('id'),
    },
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Name'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => row.getValue('name'),
    },
    {
        accessorKey: 'description',
        header: () => h('div', i18n.global.t('Description')),
        cell: ({ row }) => row.getValue('description'),
    },
    {
        accessorKey: 'position',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Position'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => row.getValue('position'),
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            return h(DropdownAction, { id: row.original.id, routeName: 'competition-type', can: row.original.can });
        },
    },
];
