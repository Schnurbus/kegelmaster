<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { DatePicker } from '@/components/ui/date-picker';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import type { Club, Player, Transaction } from '@/types/entities';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';

interface Props {
    players: Player[];
    transaction: Transaction | null;
    club?: Club;
}

const props = defineProps<Props>();

const { t } = useI18n();

const today = () => {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const form = useForm({
    club_id: props.transaction?.club_id ?? props.club?.id,
    type: props.transaction?.type ?? null,
    player_id: props.transaction?.player_id ?? null,
    amount: props.transaction?.amount ?? 0,
    auto_tip: true,
    date: props.transaction?.date ?? today(),
    notes: props.transaction?.notes ?? '',
});

const selectedDateString = ref<string | undefined>(form.date);

watch(selectedDateString, (newValue) => {
    form.date = newValue || '';
});

const isEdit = computed(() => !!props.transaction);

const submit = () => {
    if (isEdit.value) {
        form.put(route('transactions.update', props.transaction!.id as unknown as RouteParams<'transactions.update'>));
    } else {
        form.post(route('transactions.store'));
    }
};
</script>
<template>
    <Card>
        <CardHeader> </CardHeader>
        <CardContent>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-[200px_minmax(200px,_1fr)] items-center gap-y-4">
                    <Label class="font-semibold">Type</Label>
                    <div>
                        <Select v-model="form.type">
                            <SelectTrigger>
                                <SelectValue :placeholder="t('Select a type')" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem :value="1">{{ t('Base Fee') }}</SelectItem>
                                    <SelectItem :value="2">{{ t('Fee') }}</SelectItem>
                                    <SelectItem :value="3">{{ t('Payment') }}</SelectItem>
                                    <SelectItem :value="4">{{ t('Tip') }}</SelectItem>
                                    <SelectItem :value="5">{{ t('Expense') }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.type" class="text-xs text-red-500">{{ form.errors.type }}</p>
                    </div>
                    <Label>{{ t('Player') }}</Label>
                    <div>
                        <Select v-model="form.player_id" :disabled="form.type == 5">
                            <SelectTrigger>
                                <SelectValue :placeholder="t('Select a player')" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="player in props.players" :value="player.id" :key="player.id">{{ player.name }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.player_id" class="text-xs text-red-500">{{ form.errors.player_id }}</p>
                    </div>
                    <Label>{{ t('Amount') }}</Label>
                    <div>
                        <Input type="number" v-model="form.amount" placeholder="0,00" step="0.01" />
                        <p v-if="form.errors.amount" class="text-xs text-red-500">{{ form.errors.amount }}</p>
                    </div>
                    <Label v-if="!isEdit">{{ t('Auto Tip') }}</Label>
                    <div v-if="!isEdit">
                        <Switch v-model="form.auto_tip" />
                        <p v-if="form.errors.auto_tip" class="text-xs text-red-500">{{ form.errors.auto_tip }}</p>
                    </div>
                    <Label class="font-semibold">{{ t('Date') }}</Label>
                    <div>
                        <DatePicker v-model="selectedDateString" />
                        <p v-if="form.errors.date" class="text-xs text-red-500">{{ form.errors.date }}</p>
                    </div>
                    <Label class="font-semibold">{{ t('Notes') }}</Label>
                    <div>
                        <Input v-model="form.notes" />
                        <p v-if="form.errors.notes" class="text-xs text-red-500">{{ form.errors.notes }}</p>
                    </div>
                    <div class="col-span-2">
                        <div class="flex justify-center gap-x-4">
                            <Button variant="secondary" :disabled="form.processing" as-child>
                                <Link :href="route('transactions.index')">{{ t('Cancel') }}</Link>
                            </Button>
                            <Button type="submit" :disabled="form.processing || !form.isDirty">{{ isEdit ? t('Update') : t('Create') }}</Button>
                        </div>
                    </div>
                </div>
            </form>
        </CardContent>
    </Card>
</template>
