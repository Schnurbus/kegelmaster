<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { DatePicker } from '@/components/ui/date-picker';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import type { Club, Matchday } from '@/types/entities';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';

interface Props {
    matchday?: Matchday;
    club: Club;
}

const props = defineProps<Props>();

const { t } = useI18n();

const form = useForm({
    club_id: props.matchday?.club_id ?? props.club?.id,
    date: props.matchday?.date ?? null,
    notes: props.matchday?.notes ?? '',
});

// Ref für das ausgewählte Datum
const selectedDateString = ref<string | undefined>();

if (props.matchday && props.matchday.date) {
    selectedDateString.value = form.date || undefined;
}

// Watch für Änderungen an selectedDateString
watch(selectedDateString, (newValue) => {
    form.date = newValue || '';
});

const isEdit = computed(() => !!props.matchday);

const submit = () => {
    if (isEdit.value) {
        form.put(route('matchdays.update', props.matchday!.id as unknown as RouteParams<'matchdays.update'>));
    } else {
        form.post(route('matchdays.store'));
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
                        <DatePicker v-model="selectedDateString" />
                        <p v-if="form.errors.date" class="text-xs text-red-500">{{ form.errors.date }}</p>
                    </div>
                    <Label class="font-semibold">{{ t('Notes') }}</Label>
                    <div>
                        <Textarea v-model="form.notes" />
                        <p v-if="form.errors.notes" class="text-xs text-red-500">{{ form.errors.notes }}</p>
                    </div>
                    <div class="col-span-2 space-x-4 text-center">
                        <Button variant="secondary" :disabled="form.processing" as-child>
                            <Link :href="route('matchdays.index')">{{ t('Cancel') }}</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing || !form.isDirty">{{ isEdit ? t('Update') : t('Create') }}</Button>
                    </div>
                </div>
            </form>
        </CardContent>
    </Card>
</template>
