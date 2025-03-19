<script setup lang="ts">
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { CompetitionType } from '@/types/entities';
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

interface Props {
    competition_type_id: number;
}
const props = defineProps<Props>();

const { t } = useI18n();

export interface Player {
    id: number;
    name: string;
}

export interface ExtremeResult {
    amount: number;
    player: Player;
}

export interface GroupResult {
    highest?: ExtremeResult;
    lowest?: ExtremeResult;
}

interface CompetitionStatistics {
    matchday_id: number;
    competition_type: CompetitionType;
    results: Record<string, GroupResult>; // key z.B. "all", "male", "female", etc.
}

const data = ref<CompetitionStatistics>();
const loading = ref(true);

const fetchData = async () => {
    if (!props.competition_type_id) {
        return;
    }
    loading.value = true;
    try {
        const response = await fetch(route('api.statistics.last-competition', { id: props.competition_type_id }));
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const jsonData = await response.json();
        data.value = jsonData.data;
    } catch (error) {
        console.error('Fehler beim Laden der Statistics:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchData);

watch(
    () => props.competition_type_id,
    (new_competition_type_id, old_competition_type_id) => {
        if (new_competition_type_id !== old_competition_type_id) {
            fetchData();
        }
    },
);
</script>
<template>
    <div class="relative flex h-[250px] flex-col rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="vue-draggable-handle flex justify-between p-4">
            <div class="text-sm font-semibold">
                {{ data?.competition_type.name }}
            </div>
            <div>X</div>
        </div>
        <!-- <div class="flex w-full flex-1 items-center justify-center p-2"> -->
        <div v-if="data" class="no-drag flex flex-1 items-center justify-center p-4">
            <Table class="w-full">
                <TableHeader>
                    <TableRow>
                        <TableHead v-if="[1, 2].indexOf(data?.competition_type.type) > -1" colspan="2" class="font-bold">{{ t('Winner') }}</TableHead>
                        <TableHead v-if="[1, 3].indexOf(data?.competition_type.type) > -1" colspan="2" class="font-bold">{{ t('Looser') }}</TableHead>
                    </TableRow>
                    <TableRow>
                        <template v-if="data?.competition_type.type === 1">
                            <TableHead class="font-bold">{{ t('Name') }}</TableHead>
                            <TableHead class="font-bold">{{ t('Points') }}</TableHead>
                        </template>
                        <TableHead class="font-bold">{{ t('Name') }}</TableHead>
                        <TableHead class="font-bold">{{ t('Points') }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(item, index) in data?.results" :key="index">
                        <template v-if="[1, 2].indexOf(data?.competition_type.type) > -1">
                            <TableCell>{{ item.highest?.player.name }}</TableCell>
                            <TableCell>{{ item.highest?.amount }}</TableCell>
                        </template>
                        <template v-if="[1, 3].indexOf(data?.competition_type.type) > -1">
                            <TableCell>{{ item.lowest?.player.name }}</TableCell>
                            <TableCell>{{ item.lowest?.amount }}</TableCell>
                        </template>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
