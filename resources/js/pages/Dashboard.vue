<script setup lang="ts">
import Balance from '@/components/widgets/Balance.vue';
import ClubBalanceApex from '@/components/widgets/ClubBalanceApex.vue';
import ClubBalanceGraph from '@/components/widgets/ClubBalanceGraph.vue';
import LastCompetition from '@/components/widgets/LastCompetition.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Club, CompetitionType } from '@/types/entities';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
// import { GridItem, GridLayout } from 'grid-layout-plus';
// import { GridItem, GridLayout } from 'vue-grid-layout';
import { GridItem, GridLayout, type MovePayload } from '@noction/vue-draggable-grid';
import '@noction/vue-draggable-grid/styles';
import { markRaw, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface LayoutItem {
    isResizable: boolean;
    x: number;
    y: number;
    w: number;
    h: number;
    id: string;
    component: string;
    props: Record<string, any>;
}

const page = usePage<SharedData>();

interface Props {
    club?: Club;
    competitionType?: CompetitionType;
    layout: LayoutItem[];
}

const props = defineProps<Props>();

const componentMapping: Record<string, any> = {
    Balance: markRaw(Balance),
    ClubBalanceGraph: markRaw(ClubBalanceGraph),
    ClubBalanceApex: markRaw(ClubBalanceApex),
    LastCompetition: markRaw(LastCompetition),
};

const currentLayout = props.layout;
const layoutWithComponents = ref();
layoutWithComponents.value = currentLayout.map((item) => {
    return {
        ...item,
        component: componentMapping[item.component] || item.component,
    };
});

const saveDashboardLayout = (layout: any) => {
    const form = useForm({
        club_id: props.club?.id,
        user_id: page.props.auth.user.id,
        layout: layout,
    });
    form.post(route('save-dashboard'), {
        preserveState: true,
        onError: (errors) => {
            console.log(errors);
        },
    });
};

const handleMoveEnd = (payload: MovePayload) => {
    const index = currentLayout.findIndex((item) => item.id === payload.id);
    if (index !== -1) {
        currentLayout[index].x = payload.x;
        currentLayout[index].y = payload.y;
        saveDashboardLayout(currentLayout);
    }
};
</script>
<template>
    <Head title="Dashboard"></Head>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="props.club" class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <GridLayout
                v-model:layout="layoutWithComponents"
                :row-height="120"
                :is-draggable="true"
                :cols="{ lg: 2, md: 2 }"
                :colNum="5"
                horizontalShift
            >
                <template #default="{ gridItemProps }">
                    <GridItem
                        v-for="item in layoutWithComponents"
                        v-bind="gridItemProps"
                        :id="item.id"
                        :key="item.id"
                        :x="item.x"
                        :y="item.y"
                        :w="item.w"
                        :h="item.h"
                        dragAllowFrom=".vue-draggable-handle"
                        dragIgnoreFrom=".no-drag"
                        @noc-move-end="handleMoveEnd"
                    >
                        <component :is="item.component" v-bind="item.props" />
                    </GridItem>
                </template>
                <!-- <template #gridItemContent="{ item }">
                    <component :is="item.component" v-bind="item.props" />
                </template> -->
                <!-- <GridItem v-for="item in layoutWithComponents" :key="item.id">
                    <component :is="item.component" v-bind="item.props" />
                </GridItem> -->
            </GridLayout>
            <!-- <GridLayout v-model:layout="layout2" :col-num="12" :row-height="30" @noc-item-move="handleMove" @noc-item-move-end="handleMoveEnd"> -->
            <!-- <GridItem v-for="item in layout2" :key="item.i" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i" @moved="movedEvent">
                    <component :is="item.component" v-bind="item.props" />
                </GridItem> -->
            <!-- <GridItem
                    v-for="item in layoutWithComponents"
                    :key="item.i"
                    :x="item.x"
                    :y="item.y"
                    :w="item.w"
                    :h="item.h"
                    :i="item.i"
                    drag-allow-from=".vue-draggable-handle"
                    drag-ignore-from=".no-drag"
                    @moved="movedEvent"
                >
                    <component :is="item.component" v-bind="item.props" />
                </GridItem> -->
            <!-- </GridLayout> -->
            <!-- <GridLayout
                v-model:layout="layoutWithComponents"
                :auto-size="false"
                :max-rows="4"
                :is-draggable="true"
                :is-resizable="false"
                :responsive="true"
                :vertical-compact="true"
                :use-css-transforms="true"
                :cols="{ lg: 4, md: 3, sm: 2, xs: 1, xxs: 1 }"
                :row-height="220"
            >
                <GridItem
                    v-for="item in layoutWithComponents"
                    :key="item.i"
                    :x="item.x"
                    :y="item.y"
                    :w="item.w"
                    :h="item.h"
                    :i="item.i"
                    drag-allow-from=".vue-draggable-handle"
                    drag-ignore-from=".no-drag"
                    @moved="movedEvent"
                >
                    <component :is="item.component" v-bind="item.props" />
                </GridItem>
            </GridLayout> -->
        </div>
        <div v-else class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <p>
                You have no clubs yet. Please <Link :href="route('club.create')" class="underline">create one</Link> or ask a club owner to attach a
                player profile with your account.
            </p>
        </div>
    </AppLayout>
</template>
