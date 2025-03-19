<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import type { Club, CompetitionType } from '@/types/entities';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';

interface Props {
    competitionType?: CompetitionType;
    club?: Club;
}

const props = defineProps<Props>();

const { t } = useI18n();

const form = useForm({
    fee_type_id: props.competitionType?.id ?? null,
    club_id: props.competitionType?.club_id ?? props.club?.id,
    name: props.competitionType?.name ?? '',
    description: props.competitionType?.description ?? '',
    type: props.competitionType?.type ?? null,
    is_sex_specific: props.competitionType?.is_sex_specific ?? true,
    position: props.competitionType?.position ?? 0,
});

const isEdit = computed(() => !!props.competitionType);

const submit = () => {
    if (isEdit.value) {
        form.put(route('competition-type.update', props.competitionType!.id as unknown as RouteParams<'competition-type.update'>));
    } else {
        form.post(route('competition-type.store'));
    }
};
</script>
<template>
    <Card>
        <CardHeader> </CardHeader>
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
                    <Label class="font-semibold">{{ t('Type') }}</Label>
                    <div>
                        <Select v-model="form.type">
                            <SelectTrigger>
                                <SelectValue :placeholder="t('Select a type')" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem :value="1">{{ t('Winner') }} / {{ t('Looser') }}</SelectItem>
                                    <SelectItem :value="2">{{ t('Winner') }}</SelectItem>
                                    <SelectItem :value="3">{{ t('Looser') }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.type" class="text-xs text-red-500">{{ form.errors.type }}</p>
                    </div>
                    <Label class="font-semibold">{{ t('Is Sex Specific') }}</Label>
                    <Switch v-model="form.is_sex_specific" />
                    <Label class="font-semibold">{{ t('Position') }}</Label>
                    <div>
                        <Input type="number" v-model="form.position" placeholder="0" autocomplete="off" />
                        <p v-if="form.errors.position" class="text-xs text-red-500">{{ form.errors.position }}</p>
                    </div>
                    <div class="col-span-2 space-x-4 text-center">
                        <Button variant="secondary" :disabled="form.processing" as-child>
                            <Link :href="route('competition-type.index')">{{ t('Cancel') }}</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">{{ isEdit ? t('Update') : t('Create') }}</Button>
                    </div>
                </div>
            </form>
        </CardContent>
    </Card>
</template>
