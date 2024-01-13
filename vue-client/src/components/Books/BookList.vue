<script setup lang="ts">
import { ref, computed, onBeforeMount, watch } from 'vue'
import { useResource } from '@/axios'
import { useBooksStore } from '@/stores'
import { VITE_STORAGE_URL } from '@/constants'
import type { Book, BookFilter } from '@/types/Book'
import BookSearchFilter from '@/components/Books/BookSearchFilter.vue'

const store = useBooksStore()

const isLoading = ref(false)
const isFilterOpen = ref(false)

const books = computed({
  get() {
    return store.books
  },
  set(value) {
    store.books = value
  }
})

const filter = ref<BookFilter>({
  accessible: null,
  owned: null
})

async function search() {
  isLoading.value = true
  try {
    const bookResponse = await useResource<Book>('books').search(filter.value)
    books.value = bookResponse.data
  } finally {
    isLoading.value = false
  }
}

watch(filter, search, { deep: true })

onBeforeMount(search)
</script>
<template>
  <div class="full-width">
    <q-header class="bg-black q-py-md">
      <q-btn icon="tune" @click="isFilterOpen = !isFilterOpen" />
    </q-header>
    <template v-if="isLoading">
      <q-circular-progress
        indeterminate
        size="15vw"
        :thickness="0.4"
        color="blue"
        center-color="yellow"
        track-color="grey-3"
        class="q-ma-md"
      />
    </template>
    <template v-else>
      <template v-if="books.length">
        <q-card v-for="book of books" :key="book.id" class="full-width q-mt-md">
          <q-card-section horizontal class="q-pa-md">
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
      </template>
      <template v-if="!books.length"><h1 class="text-center">{{$t('books.page.empty')}}</h1></template>
    </template>
    <q-drawer v-model="isFilterOpen" overlay bordered>
      <book-search-filter v-model="filter" />
    </q-drawer>
  </div>
</template>
