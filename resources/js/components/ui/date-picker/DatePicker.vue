<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import {
  parseDate,
  type DateValue,
  getLocalTimeZone,
  DateFormatter
} from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'
import { ref, watch, computed } from 'vue'
import { toDate } from 'radix-vue/date'
import { useI18n } from 'vue-i18n'

const props = defineProps<{
  modelValue?: string
}>()

const { t, d } = useI18n();

const emit = defineEmits<{
  (event: 'update:modelValue', value?: string): void
}>()

const selectedDateValue = ref<DateValue | undefined>()
const selectedDateString = ref<string | undefined>()

function formatDate(dateValue: DateValue): string {
  const date = toDate(dateValue, getLocalTimeZone())
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}

watch(selectedDateValue, (newValue) => {
    if (newValue) {
        selectedDateString.value = formatDate(newValue)
        emit('update:modelValue', formatDate(newValue))
        calendarOpen.value = false;
  } else {
    selectedDateString.value = undefined
    emit('update:modelValue', undefined);
  }
})

watch(() => props.modelValue, (newValue) => {
  if (newValue) {
    selectedDateValue.value = parseDate(newValue)
  } else {
    selectedDateValue.value = undefined
  }
}, { immediate: true })

const calendarOpen = ref();
</script>

<template>
  <Popover v-model:open="calendarOpen">
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :class="cn(
          'w-[280px] justify-start text-left font-normal',
          !selectedDateString && 'text-muted-foreground',
        )"
      >
        <CalendarIcon class="mr-2 h-4 w-4" />
        {{ selectedDateString ? d(selectedDateString) : t("Pick a date") }}
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0">
      <Calendar v-model:modelValue="selectedDateValue" />
    </PopoverContent>
  </Popover>
</template>
