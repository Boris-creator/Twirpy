<script setup lang="ts">
import BookForm from '@/components/Books/Form/BookForm.vue'
import type { Book } from '@/types/Book'
import { useRouter } from 'vue-router'

const router = useRouter()

function onCreate(book: Book) {
  router.push(`/books/${book.id}`)
}
</script>
<template>
  <book-form @update:model-value="onCreate" />
</template>
