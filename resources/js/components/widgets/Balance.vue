<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from "axios";

interface Props {
    club_id?: number;
    player_id?: number;
}

const props = defineProps<Props>();

if ((!props.club_id && !props.player_id) || (props.club_id && props.player_id)) {
    throw new Error("Bitte gebe genau eine der Properties 'club_id' oder 'player_id' an.");
}

const widgetData = ref({ balance: 0, name: "", id: undefined });
const loading = ref(true);

const fetchData = async () => {
    loading.value = true;
    try {
        let response;
        if (props.club_id) {
            response = await axios.get(route('api.v1.club.show', { club: String(props.club_id) }), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                withCredentials: true,
                maxRedirects: 0
            });
        }
        response = props.club_id
            ? await fetch(route('api.v1.club.show', { club: String(props.club_id) }),
                {
                    headers: {
                        'AcceptAccept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include'
                })
            : await fetch(route('api.v1.player.show', { player: String(props.player_id) }),
                {
                    headers: {
                        'AcceptAccept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include'
                });
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        widgetData.value = data.data;
    } catch (error) {
        console.error('Fehler beim Laden der Balance:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchData);

watch(
    () => props.club_id,
    (newId, oldId) => {
        if (newId !== oldId) {
            fetchData();
        }
    },
);

watch(
    () => props.player_id,
    (newId, oldId) => {
        if (newId !== oldId) {
            fetchData();
        }
    },
);

const { t, n } = useI18n();
</script>
<template>
    <div class="relative flex h-[120px] flex-col rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="vue-draggable-handle flex justify-between p-2">
            <div class="ml-2 text-sm font-semibold">
                <template v-if="!loading">
                    {{ widgetData.name}}
                </template>
            </div>
        </div>
        <div class="no-drag flex flex-1 items-center justify-center p-2">
            <span v-if="!loading" class="text-xl font-bold">
                {{ n(widgetData.balance, 'currency') }}
            </span>
            <span v-else class="text-xl font-bold">
                {{ t('Loading') }}...
            </span>
        </div>
    </div>
</template>
