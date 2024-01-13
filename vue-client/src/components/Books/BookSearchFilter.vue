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
  'update:modelValue': [value: BookFilter]
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
    <q-toggle v-model="filter.accessible" :label="$t('books.filter.accessible')" />
    <q-toggle v-model="filter.owned" :label="$t('books.filter.owned')" />
  </div>
</template>
