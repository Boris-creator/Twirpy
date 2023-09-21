<script setup lang="ts">
import { computed, onBeforeMount } from 'vue'
import { useResource } from '@/axios'
import { useBooksStore } from '@/stores'
import { VITE_STORAGE_URL } from '@/constants'
import type { Book } from '@/types/Book'

const store = useBooksStore()
const books = computed({
  get() {
    return store.books
  },
  set(value) {
    store.books = value
  }
})

onBeforeMount(async () => {
  const bookResponse = await useResource<Book>('books').search()
  books.value = bookResponse.data
})
</script>
<template>
  <div class="full-width">
    <q-card v-for="book of books" :key="book.id" class="full-width q-mt-md">
      <q-card-section horizontal>
        <q-img :src="`${VITE_STORAGE_URL}${book.titleThumbnail}`" class="col-4 col-md-3" />
        <q-card-section class="col-6 col-md-7">
          <router-link :to="`/books/${book.id}`">
            <h4>
              {{ book.title }}
            </h4>
          </router-link>
        </q-card-section>
      </q-card-section>
    </q-card>
  </div>
</template>
