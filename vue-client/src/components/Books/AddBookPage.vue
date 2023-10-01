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
  <div class="form__wrapper">
    <book-form @update:model-value="onCreate" />
  </div>
</template>
<style scoped lang="scss">
.form__wrapper {
  width: 50vw;
  @media (max-width: 576px) {
    width: 100vw;
  }
  max-width: 400px;
}
</style>
