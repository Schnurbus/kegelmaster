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
import { ItemPermissions } from '@/types/entities';
import { router, useForm } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    id: number;
    can?: ItemPermissions;
    routeName: string;
}>();

const { t } = useI18n();

const editEntity = (id: number) => {
    router.visit(route(props.routeName + '.edit', { id }));
};

const deleteEntity = () => {
    const form = useForm({});
    const deleteRoute = route(props.routeName + '.destroy', { id: props.id });

    form.delete(deleteRoute);
};
</script>

<template>
    <div class="flex gap-2 pl-10">
        <Button v-if="props.can.update" @click="editEntity(props.id)" variant="outline" size="icon">
            <Pencil class="h-4 w-4" />
        </Button>
        <AlertDialog v-if="props.can.delete">
            <AlertDialogTrigger as-child>
                <Button variant="outline" size="icon"><Trash2 class="h-4 w-4" /></Button>
            </AlertDialogTrigger>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>{{ t('Are you absolutely sure?') }}</AlertDialogTitle>
                    <AlertDialogDescription>{{
                        t('Do you realy want to delete this entity? All related data will also be deleted.')
                    }}</AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <form @submit.prevent="deleteEntity" class="flex gap-2">
                        <AlertDialogCancel>{{ t('Cancel') }}</AlertDialogCancel>
                        <AlertDialogAction type="submit">{{ t('Continue') }}</AlertDialogAction>
                    </form>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
