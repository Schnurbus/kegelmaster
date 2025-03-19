<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { Club, CompetitionEntry, CompetitionType, FeeEntry, FeeType, Matchday, Player } from '@/types/entities';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const page = usePage<SharedData>();

interface Props {
    matchday: Matchday;
    club: Club;
    players: Player[];
    notAttachedPlayers: Player[];
    feeTypes: FeeType[];
    feeEntries: FeeEntry[];
    competitionTypes: CompetitionType[];
    competitionEntries: CompetitionEntry[];
}

const props = defineProps<Props>();

const { t, d } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.currentClubName ? page.props.currentClubName : t('Club'),
        href: '/dashboard',
    },
    {
        title: t('Matchday', 2),
        href: route('matchdays.index'),
    },
    {
        title: d(props.matchday.date),
        href: '',
    },
];
</script>

<template>
    <Head :title="props.matchday.date" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <div class="grid grid-cols-[200px_minmax(200px,_1fr)] items-center gap-y-4">
                    <Label class="font-semibold">Name</Label>
                    <div>
                        {{ d(props.matchday.date) }}
                    </div>
                    <Label class="font-semibold">Notes</Label>
                    <div>
                        <p>{{ props.matchday.notes }}</p>
                    </div>
                </div>
            </div>
            <Tabs default-value="fees" class="mt-4 w-full">
                <TabsList class="grid w-full grid-cols-2">
                    <TabsTrigger value="fees">{{ t('Fee', 2) }}</TabsTrigger>
                    <TabsTrigger value="competitions">{{ t('Competition', 2) }}</TabsTrigger>
                </TabsList>
                <TabsContent value="fees">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ t('Name') }}</TableHead>
                                <TableHead v-for="feeType in props.feeTypes" :key="feeType.id" class="text-right">
                                    {{ feeType.name }}
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="player in props.players" :key="player.id">
                                <TableCell class="font-medium">{{ player.name }}</TableCell>

                                <TableCell
                                    v-for="feeType in props.feeTypes.toSorted((a, b) => a.position - b.position)"
                                    :key="feeType.id"
                                    class="text-right"
                                >
                                    {{
                                        props.feeEntries.find((e) => e.fee_type_version.fee_type_id === feeType.id && e.player_id === player.id)
                                            ?.amount
                                    }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </TabsContent>
                <TabsContent value="competitions">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ t('Name') }}</TableHead>
                                <TableHead v-for="competitionType in props.competitionTypes" :key="competitionType.id" class="text-right">
                                    {{ competitionType.name }}
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="player in props.players" :key="player.id">
                                <TableCell class="font-medium">{{ player.name }}</TableCell>

                                <TableCell
                                    v-for="competitionType in props.competitionTypes.toSorted((a, b) => a.position - b.position)"
                                    :key="competitionType.id"
                                    class="text-right"
                                >
                                    {{
                                        props.competitionEntries.find(
                                            (e) => e.competition_type_id === competitionType.id && e.player_id === player.id,
                                        )?.amount
                                    }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
