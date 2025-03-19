<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import type { RoleItem } from '@/types';
import type { Club, Player } from '@/types/entities';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';
import InviteDialog from './inviteDialog.vue';

interface Props {
    player?: Player;
    roles: RoleItem[];
    club?: Club;
}

const props = defineProps<Props>();

const { t } = useI18n();

const form = useForm({
    club_id: props.player?.club_id ?? props.club?.id,
    name: props.player?.name ?? '',
    sex: props.player?.sex ?? null,
    initial_balance: props.player?.initial_balance ?? 0,
    active: props.player?.active ?? true,
    role_id: props.player?.role?.id ?? null,
});

const isEdit = computed(() => !!props.player);

const submit = () => {
    if (isEdit.value) {
        console.log(form);
        form.put(route('players.update', props.player!.id as unknown as RouteParams<'players.update'>));
    } else {
        form.post(route('players.store'));
    }
};
</script>
<template>
    <Card>
        <CardHeader></CardHeader>
        <CardContent>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-[200px_minmax(200px,_1fr)] items-center gap-y-4">
                    <Label class="font-semibold">{{ t('Name') }}</Label>
                    <div>
                        <Input v-model="form.name" :placeholder="t('Name')" v-focus />
                        <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>
                    <Label>{{ t('Sex') }}</Label>
                    <div>
                        <Select v-model="form.sex">
                            <SelectTrigger>
                                <SelectValue :placeholder="t('Select a gender')" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem :value="1">{{ t('male') }}</SelectItem>
                                    <SelectItem :value="2">{{ t('female') }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.sex" class="text-xs text-red-500">{{ form.errors.sex }}</p>
                    </div>
                    <Label class="font-semibold">{{ t('Active') }}</Label>
                    <Switch v-model="form.active" />
                    <Label>{{ t('Initial Balance') }}</Label>
                    <div>
                        <Input type="number" v-model="form.initial_balance" placeholder="0,00" step="0.01" />
                        <p v-if="form.errors.initial_balance" class="text-xs text-red-500">{{ form.errors.initial_balance }}</p>
                    </div>
                    <Label>{{ t('Role') }}</Label>
                    <div>
                        <Select v-model="form.role_id">
                            <SelectTrigger>
                                <SelectValue :placeholder="t('Select a role')" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.role_id" class="text-xs text-red-500">{{ form.errors.role_id }}</p>
                    </div>
                    <div class="col-span-2">
                        <div class="flex justify-center gap-x-4">
                            <Button variant="secondary" :disabled="form.processing" as-child>
                                <Link :href="route('players.index')">{{ t('Cancel') }}</Link>
                            </Button>
                            <InviteDialog v-if="isEdit && props.player && !props.player.user_id" :playerId="props.player.id" />
                            <Button type="submit" :disabled="form.processing">{{ isEdit ? t('Update') : t('Create') }}</Button>
                        </div>
                    </div>
                </div>
            </form>
        </CardContent>
    </Card>
</template>
