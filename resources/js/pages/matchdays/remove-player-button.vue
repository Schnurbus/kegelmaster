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
import { useForm } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
import { RouteParams } from 'ziggy-js';

const props = defineProps({
    playerId: null,
    matchdayId: null,
});

const removePlayer = () => {
    const removePlayerForm = useForm<{ player_id: number }>({
        player_id: props.playerId,
    });

    removePlayerForm.post(route('matchdays.remove-player', props.matchdayId as unknown as RouteParams<string>), {
        onError: (errors) => {
            console.log('Fehler beim Absenden des Formulars:', errors);
        },
    });
};
</script>

<template>
    <AlertDialog>
        <AlertDialogTrigger as-child>
            <Button variant="outline" size="icon"><Trash2 class="h-4 w-4" /></Button>
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                <AlertDialogDescription> Do you realy want to remove the player from this matchday? </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction @click="removePlayer()">Continue</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
