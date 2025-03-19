<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { computed, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import VueApexCharts from 'vue3-apexcharts';

interface DataPoint {
    date: string;
    balance: number;
}

interface Props {
    club_id: number;
}
const props = defineProps<Props>();

const { t, d } = useI18n();

// const chartData = ref({ date: [], balance: [] });
const chartData = ref(null);
const loading = ref(true);

const fetchData = async () => {
    loading.value = true;
    try {
        const response = await fetch(`/club/${props.club_id}/statistics`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        if (data) {
            data.data.forEach((element: { date: string | number | Date }) => {
                element.date = d(element.date);
            });
        }
        chartData.value = data.data.map((item: DataPoint) => {
            return { x: item.date, y: item.balance };
        });
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

const options = {
    chart: {
        id: 'club-balance',
        type: 'area',
        toolbar: {
            show: false,
        },
    },
    xaxis: {
        labels: {
            show: false,
        },
    },
    stroke: {
        curve: 'smooth',
    },
    tooltip: {
        y: {
            formatter: undefined,
            title: {
                formatter: (seriesName: string) => seriesName,
            },
        },
    },
    noData: {
        text: t('No Data'),
    },
};
const series = computed(() => [
    {
        name: t('Balance'),
        data: chartData.value,
    },
]);
</script>

<template>
    <div class="relative h-[250px] rounded-xl border border-sidebar-border/70 p-2 dark:border-sidebar-border">
        <div class="vue-draggable-handle flex justify-between">
            <div class="text-sm font-semibold">
                {{ t('Club Balance') }}
            </div>
            <div>X</div>
        </div>
        <PlaceholderPattern v-if="loading" />
        <VueApexCharts v-else height="218" type="area" :series="series" :options="options" />
    </div>
</template>
