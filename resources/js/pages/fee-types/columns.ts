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
        accessorKey: 'amount',
        header: () => h('div', { class: 'text-right' }, i18n.global.t('Amount')),
        cell: ({ row }) => {
            const amount = Number.parseFloat(row.getValue('amount'));
            return h('div', { class: 'text-right font-medium' }, i18n.global.n(amount, 'currency'));
        },
    },
];
