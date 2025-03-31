<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { DatePicker } from '@/components/ui/date-picker';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import type { Matchday } from '@/types/entities';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { SharedData } from '@/types';
import { DatePickerInput } from '@/components/ui/date-picker-input';
import { DateValue, parseDate } from '@internationalized/date';

interface Props {
    matchday?: Matchday;
}

const props = defineProps<Props>();

const clubId = usePage<SharedData>().props.currentClubId;

const { t } = useI18n();

const form = useForm({
    club_id: props.matchday?.club_id ?? clubId,
    date: props.matchday?.date ?? undefined,
    notes: props.matchday?.notes ?? '',
});

// Ref für das ausgewählte Datum
const dateValue = ref<DateValue>();

if (props.matchday && props.matchday.date) {
    dateValue.value = parseDate(props.matchday.date);
}

watch(dateValue, (newValue) => {
    form.date= newValue?.toString();
});

const isEdit = computed(() => !!props.matchday);

const submit = () => {
    if (isEdit.value) {
        form.put(route('matchdays.update', { id: props.matchday!.id }));
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
                <div class="grid grid-cols-[200px_minmax(200px,1fr)] items-center gap-y-4">
                    <Label class="font-semibold">{{ t('Date') }}</Label>
                    <div>
                        <DatePickerInput v-model:modelValue="dateValue" />
                        <p v-if="form.errors.date" class="text-xs text-red-500">{{ form.errors.date }}</p>
                    </div>
                    <!--                    <Label class="font-semibold">{{ t('Date') }}</Label>-->
                    <!--                    <div>-->
                    <!--                        <DatePicker v-model="selectedDateString" />-->
                    <!--                        <p v-if="form.errors.date" class="text-xs text-red-500">{{ form.errors.date }}</p>-->
                    <!--                    </div>-->
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
