<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import type { Club, Permissions, Role } from '@/types/entities';
import { useForm } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouteParams } from 'ziggy-js';

interface Props {
    role: Role | null;
    permissions: Permissions;
    club?: Club;
}

const props = defineProps<Props>();

const { t } = useI18n();

const form = useForm({
    club_id: props.role?.scope ?? props.club?.id,
    name: props.role?.name ?? '',
    is_base_fee_active: props.role?.is_base_fee_active ?? false,
    permissions: props.role?.permissions
        ? reactive(JSON.parse(JSON.stringify(props.role.permissions)))
        : Object.fromEntries(
              Object.entries(props.permissions).map(([entity, actions]) => [
                  entity,
                  Object.fromEntries(actions.map((action: string) => [action, false])),
              ]),
          ),
});

const isEdit = computed(() => !!props.role);

const submit = () => {
    if (isEdit.value) {
        form.put(route('role.update', props.role!.id as unknown as RouteParams<string>));
    } else {
        form.post(route('role.store'));
    }
};

const capitalize = (value: string) => {
    return value
        .split(' ')
        .map((word: string) => word[0].toUpperCase() + word.slice(1))
        .join(' ');
};
</script>

<template>
    <Card>
        <CardHeader> </CardHeader>
        <CardContent>
            <form class="flex w-2/3 flex-col space-y-6" @submit.prevent="submit">
                <input type="hidden" v-model="form.club_id" />
                <div class="mb-4 grid grid-cols-[auto_1fr] items-center gap-4">
                    <Label for="name">{{ t('Name') }}</Label>
                    <div>
                        <Input
                            ref="name"
                            type="text"
                            id="name"
                            v-model="form.name"
                            :placeholder="t('Role Name')"
                            autocomplete="organization"
                            v-focus
                        />
                        <div class="text-xs text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
                    </div>
                    <Label for="is_base_fee_active">{{ t('Base Fee') }}</Label>
                    <div>
                        <Switch id="is_base_fee_active" v-model="form.is_base_fee_active" />
                        <div class="text-xs text-red-500" v-if="form.errors.is_base_fee_active">{{ form.errors.is_base_fee_active }}</div>
                    </div>
                </div>
                <Card v-for="(actions, entity) in form.permissions as Record<string, Permissions>" :key="entity">
                    <CardHeader>
                        <CardTitle class="text-base">{{ t(capitalize(entity)) }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-5">
                            <div class="flex items-center space-x-2" v-for="(value, action) in actions" :key="action">
                                <template v-if="!(entity === 'club' && (action === 'list' || action === 'create' || action === 'delete'))">
                                    <Switch v-model="form.permissions![entity][action]" />
                                    <Label class="capitalize">{{ t(capitalize(action)) }}</Label>
                                </template>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Button type="submit" :disabled="form.processing">
                    {{ isEdit ? t('Update') : t('Create') }}
                </Button>
            </form>
        </CardContent>
    </Card>
</template>
