<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { AreaChart } from '@/components/ui/chart-area';
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import CustomChartTooltip from './CustomChartTooltip.vue';

interface Props {
    club_id: number;
}
const props = defineProps<Props>();

const { t, d } = useI18n();

const chartData = ref({ data: [] });
const loading = ref(true);

const fetchData = async () => {
    loading.value = true;
    try {
        const response = await fetch(`/club/${props.club_id}/statistics`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        // Hier kannst du ggf. die Daten transformieren,
        // damit sie genau in das Format passen, das deine Chart-Komponente erwartet.
        if (data) {
            data.data.forEach((element: { date: string | number | Date }) => {
                element.date = d(element.date);
            });
        }
        chartData.value = data;
    } catch (error) {
        console.error('Fehler beim Laden der Statistics:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchData);

watch(
    () => props.club_id,
    (newClubId, oldClubId) => {
        if (newClubId !== oldClubId) {
            fetchData();
        }
    },
);
</script>

<template>
    <div class="relative flex aspect-video flex-col overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="vue-draggable-handle flex justify-between p-2">
            <div class=",l-2 text-sm font-semibold">
                {{ t('Club Balance') }}
            </div>
            <div>X</div>
        </div>
        <div class="flex w-full flex-1 items-center justify-center">
            <span class="text-xl font-bold">
                <PlaceholderPattern v-if="loading" />
                <AreaChart
                    v-else-if="chartData?.data.length > 0"
                    :data="chartData!.data"
                    index="date"
                    :categories="['balance']"
                    :show-legend="false"
                    :custom-tooltip="CustomChartTooltip"
                    class="h-[100px] w-full"
                />
                <span v-else>{{ t('No Data') }}</span>
            </span>
        </div>
    </div>
</template>
