<script setup lang="ts">
import type { PropType } from 'vue'
import type { BookFilter } from '@/types/Book'
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object as PropType<BookFilter>,
    required: true
  }
})
const emits = defineEmits<{
  (e: 'update:modelValue', value: BookFilter): void
}>()

const filter = ref<BookFilter>(props.modelValue)

watch(
  filter,
  () => {
    emits('update:modelValue', filter.value)
  },
  { deep: true }
)
</script>
<template>
  <div>
    <q-toggle v-model="filter.accessible" label="Show only books I bought" />
    <q-toggle v-model="filter.owned" label="Show only books I upload" />
  </div>
</template>
