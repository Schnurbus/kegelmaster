import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import i18n from '@/i18n';
import type { Transaction } from '@/types/entities';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h } from 'vue';

export const columns: ColumnDef<Transaction>[] = [
    {
        accessorKey: 'date',
        header: () => h('div', { class: 'text-center' }, i18n.global.t('Date')),
        cell: ({ row }) => {
            const transactionDate = new Date(row.getValue('date'));
            return h('div', { class: 'font-medium' }, i18n.global.d(transactionDate));
        },
    },
    {
        id: 'matchday',
        accessorFn: (row) => row.matchday?.date,
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Matchday'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => {
            if (row.getValue('matchday')) {
                const matchdayDate = new Date(row.getValue('matchday'));
                return h('div', { class: 'font-medium' }, i18n.global.d(matchdayDate));
            } else {
                return '';
            }
        },
    },
    {
        id: 'fee_type',
        accessorFn: (row) => row.fee_entry?.fee_type_version.name,
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Fee Type'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => row.getValue('fee_type') || '',
    },
    {
        // accessorKey: 'type',
        id: 'type',
        accessorFn: (originalRow) => originalRow.type.toString(),
        filterFn: 'arrIncludesSome',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Type'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => {
            switch (row.getValue('type')) {
                case '1':
                    return h(Badge, { variant: 'default', class: 'bg-red-500' }, () => i18n.global.t('Base Fee'));
                case '2':
                    return h(Badge, { variant: 'default', class: 'bg-red-500' }, () => i18n.global.t('Fee'));
                case '3':
                    return h(Badge, { variant: 'default' }, () => i18n.global.t('Payment'));
                case '4':
                    return h(Badge, { variant: 'default' }, () => i18n.global.t('Tip'));
                case '5':
                    return h(Badge, { variant: 'default' }, () => i18n.global.t('Expense'));
                default:
                    return h(Badge, { variant: 'default' }, () => row.getValue('type'));
            }
        },
    },
    {
        accessorKey: 'amount',
        header: () => h('div', { class: 'text-right' }, i18n.global.t('Amount')),
        cell: ({ row }) => {
            const amount = Number.parseFloat(row.getValue('amount'));
            return h('div', { class: 'text-right font-medium' }, isNaN(amount) ? '-' : i18n.global.n(amount, 'currency'));
        },
    },
];
