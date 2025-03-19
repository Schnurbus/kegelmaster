<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { SharedData } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { AcceptableValue } from 'reka-ui';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const page = usePage<SharedData>();

const selectedClub = ref(page.props.currentClubId);

const updateCurrentClub = (value: AcceptableValue) => {
    if (!value) {
        return;
    }

    router.post(
        route('set-club'),
        {
            club_id: value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                router.reload();
            },
        },
    );
};
</script>

<template>
    <div>
        <Select v-model="selectedClub" class="w-full px-2" v-on:update:model-value="updateCurrentClub">
            <SelectTrigger>
                <SelectValue placeholder="Select a club" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem v-for="club in page.props.userClubs" :key="club.id" :value="club.id">{{ club.name }}</SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
