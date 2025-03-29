import { Button } from '@/components/ui/button';
import i18n from '@/i18n';
import type { CompetitionStatistics } from '@/types/entities';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h } from 'vue';

export const columns: ColumnDef<CompetitionStatistics>[] = [
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
        accessorKey: 'min_value',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Min'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => row.getValue('min_value'),
    },
    {
        accessorKey: 'max_value',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Max'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => row.getValue('max_value'),
    },
    {
        accessorKey: 'avg_value',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Avg'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => i18n.global.n(parseFloat(row.getValue('avg_value')), 'decimal'),
    },
    {
        accessorKey: 'total_entries',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [i18n.global.t('Count'), h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => row.getValue('total_entries'),
    },
];
