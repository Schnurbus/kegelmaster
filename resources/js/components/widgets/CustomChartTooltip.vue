<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { useI18n } from 'vue-i18n';

defineProps<{
    title?: string;
    data: {
        name: string;
        color: string;
        value: any;
    }[];
}>();
const { t, n } = useI18n();
</script>

<template>
    <Card class="text-sm">
        <CardContent class="flex min-w-[180px] flex-col gap-2 p-3">
            <div v-for="(item, key) in data" :key="key" class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="mr-4 h-7 w-1 rounded-full" :style="{ background: item.color }" />
                    <span>{{ t(item.name) }}</span>
                </div>
                <span class="ml-4 font-semibold">
                    <template v-if="item.name === 'balance'">
                        {{ n(item.value, 'currency') }}
                    </template>
                    <template v-else>
                        {{ item.value }}
                    </template>
                </span>
            </div>
        </CardContent>
    </Card>
</template>
