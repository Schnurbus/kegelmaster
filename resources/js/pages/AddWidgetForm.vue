<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription, DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog/index.js';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select/index.js';
import { Button } from '@/components/ui/button/index.js';
import {v7 as uuidv7} from "uuid";
import { computed, onMounted, ref } from 'vue';
import { LayoutItem } from '@/types';

interface Props {
    clubId: number;
    playerId: number;
    modelValue: LayoutItem[];
}

// Definiere emits für v-model
const emit = defineEmits<{
    (e: 'update:modelValue', value: LayoutItem[]): void;
}>();

const props = defineProps<Props>();

const layout = computed({
    get: () => props.modelValue,
    set: (value: LayoutItem[]) => emit('update:modelValue', value)
});

const loading = ref(true);
const competitionTypes = ref();

const fetchData = async () => {
    loading.value = true;
    try {
        const response = await fetch(route('api.v1.competition-types.index',
            { club: String(props.clubId) }));
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        competitionTypes.value = data;
    } catch (error) {
        console.error('Fehler beim Laden der Balance:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchData);

const widgetTypes = [
    {
        name: "club-balance",
        label: "Club Balance",
        code: {"x":0,"y":0,"w":1,"h":1,"component":"Balance","props":{"club_id": props.clubId}},
    },
    {
        name: "club-balance-graph",
        label: "Club Balance (Graph)",
        code: {"x":0,"y":0,"w":2,"h":2,"component":"BalanceGraph","props":{"club_id": props.clubId}},
    },
    {
        name: "player-balance",
        label: "Player Balance",
        code: {"x":0,"y":0,"w":1,"h":1,"component":"Balance","props":{"player_id": props.playerId}},
    },
    {
        name: "last-competition",
        label: "Last Competition",
        code: {"x":0,"y":0,"w":2,"h":2,"component":"LastCompetition","props":{"competition_type_id": null}},
    }
]

const open = ref(false);
const selectedWidgetType = ref<string | undefined>(undefined);
const selectedCompetition = ref<string | undefined>(undefined);

const addWidget = () => {
    const widgetType = widgetTypes.find((e) => e.name === selectedWidgetType.value);

    if (widgetType) {
        console.log(`Füge Widget vom Typ ${widgetType.name} hinzu`);

        // Tiefen-Kopie des Widget-Codes erstellen und eine neue ID generieren
        const newWidget = JSON.parse(JSON.stringify(widgetType.code));
        newWidget.id = uuidv7(); // Neue ID generieren, damit jedes Widget einzigartig ist

        if (widgetType.name === 'last-competition') {
            console.log(`Setze competition type auf ${selectedCompetition.value}`);
            newWidget.props.competition_type_id = selectedCompetition.value;
        }

        // Setze Position für das neue Widget
        positionNewWidget(newWidget);

        // Füge das neue Widget zum Layout hinzu
        layout.value = [...layout.value, newWidget];

        // Zurücksetzen des ausgewählten Widget-Typs und Dialog schließen
        selectedWidgetType.value = undefined;
        open.value = false;
    }
}

const positionNewWidget = (widget: LayoutItem) => {
    // Standardposition, falls es das erste Widget ist
    if (!layout.value.length) {
        widget.x = 0;
        widget.y = 0;
        return;
    }

    // Finde die höchste y-Position + Höhe
    const maxY = Math.max(...layout.value.map(item => item.y + item.h));

    // Positioniere das neue Widget unter den bestehenden Widgets
    widget.y = maxY;
    widget.x = 0;
};

</script>
<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button class="absolute bottom-4 right-4 rounded-full">+</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Edit Dashboard</DialogTitle>
                <DialogDescription>
                    Add widgets to dashboard
                </DialogDescription>
            </DialogHeader>
                <Select v-model="selectedWidgetType">
                    <SelectTrigger>
                        <SelectValue placeholder="Widget Type" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="widgetType in widgetTypes" :value="widgetType.name" :key="widgetType.name">{{widgetType.label}}</SelectItem>
                    </SelectContent>
                </Select>
                <Select v-model="selectedCompetition" :disabled="!selectedWidgetType || selectedWidgetType != 'last-competition'">
                    <SelectTrigger>
                        <SelectValue placeholder="Competitions" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="competitionType in competitionTypes.data"
                                    :value="competitionType.id" :key="competitionType.id">
                            {{competitionType.name}}
                        </SelectItem>
                    </SelectContent>
                </Select>
            <DialogFooter>
                <Button @click="addWidget">
                    Save changes
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

</template>
