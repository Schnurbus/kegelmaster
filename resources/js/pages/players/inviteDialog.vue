<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { router, useForm } from '@inertiajs/vue3';
import { RouteParams } from 'ziggy-js';

interface Props {
    playerId: number;
}

const props = defineProps<Props>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('players.invite.store', props.playerId as unknown as RouteParams<'players.invite.store'>), {
        onSuccess: () => {
            router.visit(route('players.index'));
        },
        onError: (errors) => {
            console.log('Fehler beim Absenden des Formulars:', errors);
        },
    });
};
</script>

<template>
    <div>
        <form @submit.prevent="submit" id="playerInvitationForm">
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="outline"> Invite </Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>Invite player</DialogTitle>
                        <DialogDescription> Invite another user to claim this player. </DialogDescription>
                    </DialogHeader>

                    <div class="flex flex-col gap-2">
                        <Label>Email</Label>
                        <Input v-model="form.email" placeholder="Email address" required />
                    </div>

                    <DialogFooter>
                        <DialogClose asChild>
                            <Button type="button" variant="secondary"> Cancel </Button>
                        </DialogClose>
                        <Button type="submit" form="playerInvitationForm"> Invite Player </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </form>
    </div>
</template>
