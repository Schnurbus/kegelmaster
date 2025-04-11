import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import i18n from '@/i18n';
import type { Player } from '@/types/entities';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h } from 'vue';

export const columns: ColumnDef<Player>[] = [
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
        id: 'role',
        accessorFn: (row) => row.role.name,
        filterFn: 'arrIncludesSome',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Role'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => row.getValue('role'),
    },
    {
        id: 'active',
        accessorFn: (row) => (row.active ? '1' : '0'),
        filterFn: 'arrIncludesSome',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Active'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => {
            if (row.getValue('active') === '1') {
                return h(Badge, { variant: 'default' }, () => i18n.global.t('Active'));
            } else {
                return h(Badge, { variant: 'secondary' }, () => i18n.global.t('Inactive'));
            }
        },
    },
    {
        accessorKey: 'balance',
        header: () => h('div', { class: 'text-right' }, i18n.global.t('Balance')),
        cell: ({ row }) => {
            const balance = Number.parseFloat(row.getValue('balance'));
            return h('div', { class: 'text-right font-medium' }, isNaN(balance) ? '-' : i18n.global.n(balance, 'currency'));
        },
    },
];
