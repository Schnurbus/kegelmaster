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
import type { FeeEntry, FeeType, Matchday, Player } from '@/types/entities';
import { useForm } from '@inertiajs/vue3';
import { Check, Pencil, Trash2, X } from 'lucide-vue-next';
import { computed, nextTick, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';

interface Props {
    matchday: Matchday;
    feeTypes: FeeType[];
    players: Player[];
    feeEntries: FeeEntry[];
}

const props = defineProps<Props>();

const { t } = useI18n();

const feeEntryMap = computed(() => {
    const map: { [playerId: number]: { [feeTypeId: number]: FeeEntry } } = {};
    props.feeEntries.forEach((entry) => {
        if (!map[entry.player_id]) {
            map[entry.player_id] = {};
        }
        map[entry.player_id][entry.fee_type_version_id] = entry;
    });
    return map;
});

const editingRows = ref<{ [playerId: number]: boolean }>({});
const isRowEditing = ref<boolean>(false);

// useForm für die Bearbeitung der FeeEntries
const form = useForm<{ entries: { id: number; player_id: number; amount: number }[] }>({
    entries: [],
});

async function setFocusToId(id: number) {
    const elementName = 'input_' + id + '_0';
    await nextTick();
    document.getElementById(elementName)?.focus();
}

const startEditing = (playerId: number) => {
    form.entries = Object.values(feeEntryMap.value[playerId] || []).map((entry) => ({
        id: entry.id,
        player_id: playerId,
        amount: entry.amount,
        matchday_id: props.matchday.id,
        fee_type_version_id: entry.fee_type_version_id,
    }));
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
    if (form.entries.length === 0) return;

    form.put(route('fee-entries.bulk-update', { matchday: props.matchday.id }), {
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

    removePlayerForm.post(route('matchdays.remove-player', props.matchday.id as unknown as RouteParams<string>));
};
const getEntryById = (id: number) => {
    return form.entries.find((entry) => entry.id === id);
};

const sortedPlayers = computed(() => [...props.players].sort((a, b) => (a.name < b.name ? -1 : 1)));
const sortedfeeTypes = computed(() => [...props.feeTypes].sort((a, b) => (a.position < b.position ? -1 : 1)));
</script>
<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>{{ t('Name') }}</TableHead>
                <TableHead v-for="feeType in sortedfeeTypes" :key="feeType.id" class="text-right">
                    {{ feeType.name }}
                </TableHead>
                <TableHead>{{ t('Action', 2) }}</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="player in sortedPlayers" :key="player.id">
                <TableCell class="font-medium">{{ player.name }}</TableCell>

                <TableCell v-for="(feeType, index) in sortedfeeTypes" :key="feeType.id" class="text-right">
                    <Input
                        v-if="editingRows[player.id]"
                        :id="'input_' + player.id + '_' + index"
                        v-model="getEntryById(feeEntryMap[player.id][feeType.latest_version.id].id)!.amount"
                        type="number"
                        class="text-right"
                    />
                    <span v-else>
                        {{ feeEntryMap[player.id][feeType.latest_version.id].amount ?? '—' }}
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
</template>
