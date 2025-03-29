import DropdownAction from '@/components/tables/data-table-dropdown.vue';
import { Button } from '@/components/ui/button';
import i18n from '@/i18n';
import { Role } from '@/types/entities';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown, Check, X } from 'lucide-vue-next';
import { h } from 'vue';

export const columns: ColumnDef<Role>[] = [
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
        // cell: ({ row }) => row.getValue('name'),
        cell: ({ row }) => row.getValue('name'),
    },
    {
        accessorKey: 'is_base_fee_active',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Base Fee'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        // cell: ({ row }) => (row.getValue('is_base_fee_active') ? 'Yes' : 'No'),
        cell: ({ row }) =>
            row.getValue('is_base_fee_active') ? h(Check, { class: 'color: text-green-500' }) : h(X, { class: 'color: text-red-500' }),
    },
];
