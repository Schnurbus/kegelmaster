<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Club } from '@/types/entities';
import { Link, useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';

interface Props {
    club?: Club;
}

const props = defineProps<Props>();

const { t } = useI18n();

const form = useForm({
    club_id: props.club?.id || null,
    name: props.club?.name || '',
    base_fee: props.club?.base_fee || 0,
    initial_balance: props.club?.initial_balance || 0,
});

const isEdit = computed(() => !!props.club);

const submit = () => {
    if (isEdit.value) {
        form.put(route('club.update', {id: props.club!.id }));
    } else {
        form.post(route('club.store'));
    }
};
</script>
<template>
    <form class="flex w-2/3 flex-col space-y-6" @submit.prevent="submit">
        <Label for="name">{{ t('Club Name') }}</Label>
        <p class="mb-0 text-xs text-red-500" v-if="form.errors.name">{{ form.errors.name }}</p>
        <Input type="text" id="name" v-model="form.name" placeholder="Club Name" autocomplete="organization" v-focus />
        <Label for="baseFee">{{ t('Base Fee') }}</Label>
        <div class="text-xs text-red-500" v-if="form.errors.base_fee">{{ form.errors.base_fee }}</div>
        <Input id="baseFee" type="number" v-model="form.base_fee" placeholder="0.00" step="0.01" autocomplete="off" />
        <template v-if="!isEdit">
            <Label for="initialBalance">{{ t('Initial Balance') }}</Label>
            <div class="text-xs text-red-500" v-if="form.errors.initial_balance">{{ form.errors.initial_balance }}</div>
            <Input id="initialBalance" type="number" placeholder="0.00" v-model="form.initial_balance" step="0.01" autocomplete="off" />
        </template>
        <div class="col-span-2 space-x-4 text-center">
            <Button variant="secondary" :disabled="form.processing" as-child>
                <Link :href="route('club.index')">Cancel</Link>
            </Button>
            <Button type="submit" :disabled="form.processing || !form.isDirty">
                <Loader2 class="mr-2 h-4 w-4 animate-spin" v-if="form.processing" />
                {{ isEdit ? 'Update' : 'Create' }}</Button
            >
        </div>
    </form>
</template>
