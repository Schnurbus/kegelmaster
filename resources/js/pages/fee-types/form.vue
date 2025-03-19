<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Club, FeeType } from '@/types/entities';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';

interface Props {
    feeType?: FeeType;
    club?: Club;
}

const props = defineProps<Props>();

const { t } = useI18n();

const form = useForm({
    fee_type_id: props.feeType?.id ?? null,
    club_id: props.feeType?.club_id ?? props.club?.id,
    name: props.feeType?.name ?? '',
    description: props.feeType?.description ?? '',
    amount: props.feeType?.amount ?? 0,
    position: props.feeType?.position ?? 0,
});

const isEdit = computed(() => !!props.feeType);

const submit = () => {
    if (isEdit.value) {
        form.put(route('fee-type.update', props.feeType!.id as unknown as RouteParams<'fee-type.update'>));
    } else {
        form.post(route('fee-type.store'));
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
                    <Label class="font-semibold">{{ t('Description') }}</Label>
                    <div>
                        <Input v-model="form.description" :placeholder="t('Description')" autocomplete="off" />
                        <p v-if="form.errors.description" class="text-xs text-red-500">{{ form.errors.description }}</p>
                    </div>
                    <Label>{{ t('Amount') }}</Label>
                    <div>
                        <Input type="number" v-model="form.amount" placeholder="0,00" step="0.01" autocomplete="off" />
                        <p v-if="form.errors.amount" class="text-xs text-red-500">{{ form.errors.amount }}</p>
                    </div>
                    <Label>{{ t('Position') }}</Label>
                    <div>
                        <Input type="number" v-model="form.position" placeholder="0" autocomplete="off" />
                        <p v-if="form.errors.position" class="text-xs text-red-500">{{ form.errors.position }}</p>
                    </div>
                    <div class="col-span-2 space-x-4 text-center">
                        <Button variant="secondary" :disabled="form.processing" as-child>
                            <Link :href="route('fee-type.index')">{{ t('Cancel') }}</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">{{ isEdit ? t('Update') : t('Create') }}</Button>
                    </div>
                </div>
            </form>
        </CardContent>
    </Card>
</template>
