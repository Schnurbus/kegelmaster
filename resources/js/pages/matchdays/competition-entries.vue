<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { CompetitionEntry, CompetitionType, FeeEntry, Matchday, Player } from '@/types/entities';
import { useForm } from '@inertiajs/vue3';
import { Check, Pencil, Trash2, X } from 'lucide-vue-next';
import { computed, nextTick, ref } from 'vue';
import { useI18n } from 'vue-i18n';

interface Props {
    matchday: Matchday;
    competitionTypes: CompetitionType[];
    players: Player[];
    competitionEntries: CompetitionEntry[];
}

const props = defineProps<Props>();

const { t } = useI18n();

const competitionEntryMap = computed(() => {
    const map: { [playerId: number]: { [competitionTypeId: number]: Partial<CompetitionEntry> } } = {};
    props.players.forEach((player) => {
        props.competitionTypes.forEach((competitionType) => {
            if (!map[player.id]) {
                map[player.id] = {};
            }

            const competitionEntry = props.competitionEntries.find(
                (entry) => entry.competition_type_id === competitionType.id && entry.player_id === player.id,
            );

            map[player.id][competitionType.id] = competitionEntry
                ? competitionEntry
                : {
                    matchday_id: props.matchday.id,
                    player_id: player.id,
                    competition_type_id: competitionType.id,
                    amount: 0,
                };

        });
    });
    return map;
});

const editingRows = ref<{ [playerId: number]: boolean }>({});
const isRowEditing = ref<boolean>(false);

// useForm für die Bearbeitung der FeeEntries
const form = useForm<{
    entries: { id: number | undefined; player_id: number; amount: number | undefined; matchday_id: number; competition_type_id: number }[];
    matchday_id: number;
}>({
    entries: [],
    matchday_id: props.matchday.id,
});

async function setFocusToId(id: number) {
    const elementName = 'input_' + id + '_0';
    await nextTick();
    document.getElementById(elementName)?.focus();
}

const startEditing = (playerId: number) => {
    editingRows.value[playerId] = true;
    isRowEditing.value = true;
    setFocusToId(playerId);
};

const cancelEditing = (playerId: number) => {
    editingRows.value[playerId] = false;
    form.entries = [];
    isRowEditing.value = false;
};

const saveRow = async (playerId: number) => {
    form.entries = Object.values(competitionEntryMap.value[playerId]).map((entry) => ({
        id: entry.id || undefined,
        player_id: entry.player_id!,
        competition_type_id: entry.competition_type_id!,
        matchday_id: entry.matchday_id!,
        amount: entry.amount || 0,
    }));


    form.put(route('competition-entries.bulk-update', { matchday: props.matchday.id }), {
        preserveScroll: true,
        onSuccess: () => {
            editingRows.value[playerId] = false;
            form.entries = [];
            isRowEditing.value = false;
        },
    });
};

const removePlayer = (playerId: number) => {
    const removePlayerForm = useForm<{ player_id: number }>({
        player_id: playerId,
    });

    removePlayerForm.post(route('matchdays.remove-player', {id: props.matchday.id }));
};
const sortedPlayers = computed(() => [...props.players].sort((a, b) => (a.name < b.name ? -1 : 1)));
</script>
<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>{{ t('Name') }}</TableHead>
                <TableHead v-for="competitionType in props.competitionTypes" :key="competitionType.id" class="text-right">
                    {{ competitionType.name }}
                </TableHead>
                <TableHead>{{ t('Action', 2) }}</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="player in sortedPlayers" :key="player.id">
                <TableCell class="font-medium">{{ player.name }}</TableCell>

                <TableCell
                    v-for="(competitionType, index) in props.competitionTypes.toSorted((a, b) => a.position - b.position)"
                    :key="competitionType.id"
                    class="text-right"
                >
                    <Input
                        v-if="editingRows[player.id]"
                        :id="'input_' + player.id + '_' + index"
                        v-model="competitionEntryMap[player.id][competitionType.id].amount"
                        type="number"
                        class="text-right"
                        :v-focus="index === 0"
                    />
                    <span v-else>
                        {{ competitionEntryMap[player.id][competitionType.id].amount ?? '—' }}
                    </span>
                </TableCell>

                <TableCell>
                    <div class="flex gap-x-2">
                        <template v-if="!editingRows[player.id]">
                            <Button @click="startEditing(player.id)" variant="outline" size="icon" :disabled="isRowEditing">
                                <Pencil class="h-4 w-4" />
                            </Button>
                            <AlertDialog>
                                <AlertDialogTrigger as-child>
                                    <Button variant="outline" size="icon" :disabled="isRowEditing"><Trash2 class="h-4 w-4" /></Button>
                                </AlertDialogTrigger>
                                <AlertDialogContent>
                                    <AlertDialogHeader>
                                        <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                                        <AlertDialogDescription> Do you realy want to remove the player from this matchday? </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                                        <AlertDialogAction @click="removePlayer(player.id)">Continue</AlertDialogAction>
                                    </AlertDialogFooter>
                                </AlertDialogContent>
                            </AlertDialog>
                        </template>
                        <template v-else>
                            <Button @click="saveRow(player.id)" variant="outline" size="icon" class="text-green-600">
                                <Check class="h-4 w-4" />
                            </Button>
                            <Button @click="cancelEditing(player.id)" variant="outline" size="icon" class="text-red-600">
                                <X class="h-4 w-4" />
                            </Button>
                        </template>
                    </div>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
    <!-- {{ feeEntryMap }} -->
</template>
