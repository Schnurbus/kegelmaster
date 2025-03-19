<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
</script>

<template>
    <div>
        <Select v-model="$i18n.locale">
            <SelectTrigger>
                <SelectValue />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem value="de">DE</SelectItem>
                    <SelectItem value="en">EN</SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
