<script setup lang="ts">
import Balance from '@/components/widgets/Balance.vue';
import ClubBalanceApex from '@/components/widgets/ClubBalanceApex.vue';
import ClubBalanceGraph from '@/components/widgets/ClubBalanceGraph.vue';
import BalanceGraph from '@/components/widgets/BalanceGraph.vue';
import LastCompetition from '@/components/widgets/LastCompetition.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem, LayoutItem } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { GridItem, GridLayout } from '@noction/vue-draggable-grid';
import '@noction/vue-draggable-grid/styles';
import { computed, markRaw, ref, watch } from 'vue';
import AddWidgetForm from '@/pages/AddWidgetForm.vue';
import { XCircle } from 'lucide-vue-next'; // Icon für den Löschbutton

const components = {
    Balance,
    BalanceGraph,
    ClubBalanceApex,
    ClubBalanceGraph,
    LastCompetition
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage<SharedData>();

interface Props {
    playerId?: number;
    layout?: LayoutItem[];
}

const props = withDefaults(defineProps<Props>(),
    {
        layout: () => [],
    });

const dashboardLayout = ref<LayoutItem[]>(props.layout || []);

const processedLayout = computed(() => {
    return dashboardLayout.value.map(item => {
        const layoutItem = { ...item };

        // Wenn die Komponente als String spezifiziert ist, ersetze sie durch die tatsächliche Komponente
        if (typeof item.component === 'string' && components[item.component as keyof typeof components]) {
            layoutItem.component = markRaw(components[item.component as keyof typeof components]);
        }

        return layoutItem;
    });
});

const currentLayout = ref(processedLayout.value);

watch(processedLayout, (newValue) => {
    currentLayout.value = newValue;
});

const saveDashboardLayout = (layout: any) => {
    console.log("Speichere das Layout");

    const serializedLayout = layout.map((item: any) => {
        // Erstelle eine Kopie des Layout-Items
        const serializedItem = { ...item };

        // Wenn die Komponente ein Objekt ist (direkte Komponente),
        // ersetze sie durch ihren Namen
        if (typeof serializedItem.component !== 'string') {
            // Suche den Namen der Komponente im components-Objekt
            for (const [name, component] of Object.entries(components)) {
                if (serializedItem.component === component) {
                    serializedItem.component = name;
                    break;
                }
            }

            // Fallback, falls die Komponente nicht gefunden wurde
            if (typeof serializedItem.component !== 'string') {
                // Nimm den Anzeigenamen oder den Konstruktornamen, falls verfügbar
                serializedItem.component = serializedItem.component.name ||
                    serializedItem.component.__name ||
                    'UnknownComponent';
            }
        }

        // Stelle sicher, dass keine Reaktivitäts-Proxies gesendet werden
        return JSON.parse(JSON.stringify(serializedItem));
    });

    const form = useForm({
        club_id: page.props.currentClubId,
        user_id: page.props.auth.user.id,
        layout: serializedLayout,
    });
    form.post(route('save-dashboard'), {
        preserveState: true,
        onError: (errors) => {
            console.log(errors);
        },
    });
};

const onWidgetAdded = () => {
    saveDashboardLayout(dashboardLayout.value);
}

const removeWidget = (widgetId: string) => {
    const index = dashboardLayout.value.findIndex(item => item.id === widgetId);

    if (index !== -1) {
        dashboardLayout.value = [
            ...dashboardLayout.value.slice(0, index),
            ...dashboardLayout.value.slice(index + 1)
        ];
        saveDashboardLayout(dashboardLayout.value);
    } else {
        console.warn(`Widget mit ID ${widgetId} wurde nicht gefunden`);
    }
};

const handleLayoutUpdate = (newLayout: any) => {
    // Extrahiere die wichtigen Layout-Informationen (Position, Größe) und
    // aktualisiere dashboardLayout mit diesen Werten
    dashboardLayout.value = dashboardLayout.value.map(item => {
        const updatedItem = newLayout.find((layoutItem: any) => layoutItem.id === item.id);
        if (updatedItem) {
            return {
                ...item,
                x: updatedItem.x,
                y: updatedItem.y,
                w: updatedItem.w,
                h: updatedItem.h
            };
        }
        return item;
    });

    // Aktualisiere currentLayout, falls nötig
    currentLayout.value = newLayout;
};

const handleMoveEnd = () => {
    saveDashboardLayout(dashboardLayout.value);
};
</script>
<template>
    <Head title="Dashboard"></Head>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="page.props.currentClubId" class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <AddWidgetForm
                :club-id="page.props.currentClubId!"
                :player-id="props.playerId!"
                v-model="dashboardLayout"
                @update:modelValue="onWidgetAdded"
            />
            <GridLayout
                v-if="currentLayout.length > 0"
                :layout="currentLayout"
                @update:layout="handleLayoutUpdate"
                :row-height="120"
                :is-draggable="true"
                :cols="{ lg: 2, md: 2 }"
                :colNum="5"
                :horizontalShift="true"
                :verticalCompact="true"
            >
                <template #default="{ gridItemProps }">
                    <GridItem
                        v-for="item in currentLayout"
                        v-bind="gridItemProps"
                        :id="item.id"
                        :key="item.id"
                        :x="item.x"
                        :y="item.y"
                        :w="item.w"
                        :h="item.h"
                        :isResizable="false"
                        dragAllowFrom=".vue-draggable-handle"
                        dragIgnoreFrom=".no-drag"
                        @noc-move-end="handleMoveEnd"
                        class="relative hover:*:visible"
                    >
                        <button
                            @click.stop="removeWidget(item.id)"
                            class="absolute -top-3 -right-3 z-10 bg-white rounded-full shadow-md p-1 hover:bg-red-100 transition-colors duration-200 no-drag invisible"
                            title="Widget entfernen"
                        >
                            <XCircle class="h-5 w-5 text-red-500" />
                        </button>
                        <component :is="item.component" v-bind="item.props" />
                    </GridItem>
                </template>
            </GridLayout>
        </div>
        <div v-else class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <p>
                You have no clubs yet. Please <Link :href="route('club.create')" class="underline">create one</Link> or ask a club owner to attach a
                player profile with your account.
            </p>
        </div>
    </AppLayout>
</template>
<style>
.vue-grid-item.vue-grid-placeholder {
    z-index:2;
    -webkit-user-select:none;
    user-select:none;
    background: slategray;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    -o-border-radius: 10px;
    -ms-border-radius: 10px;
    border-radius: 10px;
    opacity:.2;
    transition-duration:.1s
}
</style>


