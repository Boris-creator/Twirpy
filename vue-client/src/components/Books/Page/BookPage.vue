<script setup lang="ts">
import type { Nullable } from '@/types/utils'
import type { Book } from '@/types/Book'
import { useQuery } from 'vue-query'
import { useBooksStore } from '@/stores'
import { api } from '@/axios'
import { VITE_API_URL, VITE_STORAGE_URL } from '@/constants'
import BookPageTemplate from '@/components/Books/Page/BookPageTemplate.vue'

const props = defineProps({
  id: {
    type: Number,
    required: true
  }
})

const { data: book, isLoading } = useQuery<Nullable<Book>>(
  'book',
  async () => (await api.get(`/books/${props.id}`)).data,
  {
    placeholderData: useBooksStore().findByID(+props.id)
  }
)
</script>
<template>
  <!--<template v-if="isLoading">
    <q-circular-progress
      indeterminate
      size="15vw"
      :thickness="0.4"
      color="blue"
      center-color="yellow"
      track-color="grey-3"
      class="q-ma-md"
    />
  </template>-->
  <template v-if="book">
    <!--    <div class="full-width" :class="{ column: $q.screen.sm }">
      <q-img :src="`${VITE_STORAGE_URL}${book?.titleThumbnail}`" placeholder-src="" class="col-5" />
      <div class="col-7 q-px-md">
        <h1>
          {{ book.title }}
        </h1>
        <h3 v-if="book.year">{{ book.year }}</h3>
        <div class="text-h4">
          <a v-if="book.accessible" :href="`${VITE_API_URL}/books/${book.id}/download`" download
            >download</a
          >
          <span class="block"> downloaded by {{ book.downloads_count }} users </span>
        </div>
      </div>
    </div>-->
    <book-page-template>
      <template #title>
        {{ book.title }}
      </template>
      <template #image>
        <q-img :src="`${VITE_STORAGE_URL}${book?.titleThumbnail}`" placeholder-src="" />
      </template>
      <template #year>
        {{ book.year }}
      </template>
      <template #downloads>
        <a v-if="book.accessible" :href="`${VITE_API_URL}/books/${book.id}/download`" download
          >download</a
        >
        <span class="block"> downloaded by {{ book.downloads_count }} users </span>
      </template>
    </book-page-template>
  </template>
</template>
