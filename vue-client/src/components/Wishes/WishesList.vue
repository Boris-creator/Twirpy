<script setup lang="ts">
import type { PropType } from 'vue'
import type { Wish } from '@/types/Wish'

defineProps({
  wishes: {
    type: Array as PropType<Array<Wish>>,
    default: () => []
  }
})
</script>
<template>
  <div>
    <div v-for="wish of wishes" :key="wish.id">
      <p class="text-h4">{{ wish.title }}</p>
      <slot name="actions" :wish="wish" />
    </div>
  </div>
</template>
