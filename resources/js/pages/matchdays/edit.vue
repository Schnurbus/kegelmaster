<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import type { Club, CompetitionEntry, CompetitionType, FeeEntry, FeeType, Matchday, Player } from '@/types/entities';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';
import CompetitionEntries from './competition-entries.vue';
import FeeEntries from './fee-entries.vue';
import Form from './form.vue';

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
        title: page.props.currentClubName ? page.props.currentClubName : 'Club',
        href: '/dashboard',
    },
    {
        title: t('Matchday', 2),
        href: route('matchdays.index'),
    },
    {
        title: d(props.matchday!.date),
        href: '',
    },
];

const addPlayerForm = useForm({
    player_id: null,
});

const addPlayer = () => {
    addPlayerForm.post(route('matchdays.add-player', props.matchday.id as unknown as RouteParams<string>));
};
</script>

<template>
    <Head :title="t('Edit Matchday')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col">
                <Form :matchday="props.matchday" :club="props.club" />
                <Card class="mt-4">
                    <CardHeader>
                        <form id="addPlayerForm" @submit.prevent="addPlayer" class="mt-4 flex gap-4">
                            <Select v-model="addPlayerForm.player_id" class="w-[200px]">
                                <SelectTrigger>
                                    <SelectValue :placeholder="t('Select a player')" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="player in notAttachedPlayers.toSorted((a, b) => (a.name > b.name ? 1 : -1))"
                                            :value="player.id"
                                            :key="player.id"
                                            >{{ player.name }}</SelectItem
                                        >
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <Button :disabled="notAttachedPlayers.length == 0">{{ t('Add Player') }}</Button>
                        </form>
                    </CardHeader>
                    <CardContent>
                        <Tabs default-value="fees" class="mt-4 w-full">
                            <TabsList class="grid w-full grid-cols-2">
                                <TabsTrigger value="fees">{{ t('Fee', 2) }}</TabsTrigger>
                                <TabsTrigger value="competitions">{{ t('Competition', 2) }}</TabsTrigger>
                            </TabsList>
                            <TabsContent value="fees">
                                <FeeEntries
                                    :matchday="props.matchday"
                                    :players="props.players"
                                    :feeTypes="props.feeTypes"
                                    :feeEntries="props.feeEntries"
                                />
                            </TabsContent>
                            <TabsContent value="competitions">
                                <CompetitionEntries
                                    :matchday="props.matchday"
                                    :players="props.players"
                                    :competition-types="props.competitionTypes"
                                    :competition-entries="props.competitionEntries"
                                />
                            </TabsContent>
                        </Tabs>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
