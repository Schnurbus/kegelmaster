import DropdownAction from '@/components/tables/data-table-dropdown.vue';
import { Button } from '@/components/ui/button';
import i18n from '@/i18n';
import { Club } from '@/types/entities';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h } from 'vue';

export const columns: ColumnDef<Club>[] = [
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
        id: 'owner',
        accessorFn: (row) => row.user.name,
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Owner'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => row.getValue('owner'),
    },
    {
        accessorKey: 'players_count',
        header: () => h('div', { class: 'text-right pr-10' }, i18n.global.t('Player', 2)),
        cell: ({ row }) => {
            return h('div', { class: 'text-right font-medium' }, isNaN(row.getValue('players_count')) ? '-' : row.getValue('players_count'));
        },
    },
    {
        accessorKey: 'balance',
        header: () => h('div', { class: 'text-right pr-10' }, i18n.global.t('Balance')),
        cell: ({ row }) => {
            const balance = Number.parseFloat(row.getValue('balance'));
            return h('div', { class: 'text-right font-medium' }, isNaN(balance) ? '-' : i18n.global.n(balance, 'currency'));
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            return h(DropdownAction, { id: row.original.id, routeName: 'club', can: row.original.can });
        },
    },
];
