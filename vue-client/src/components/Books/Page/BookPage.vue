<script setup lang="ts">
import { ref } from 'vue'
import { useQuery, useQueryClient } from 'vue-query'
import { useRouter } from 'vue-router'
import { api, useResource } from '@/axios'
import { CHUNKS } from '@/router'
import { useBooksStore } from '@/stores'
import { VITE_API_URL, VITE_STORAGE_URL } from '@/constants'
import BookPageTemplate from '@/components/Books/Page/BookPageTemplate.vue'
import BookForm from '@/components/Books/Form/BookForm.vue'
import CommentsThread from '@/components/Comments/CommentsThread.vue'
import type { AxiosError } from 'axios'
import type { Nullable } from '@/types/utils'
import type { Book, BookBibliography } from '@/types/Book'

const router = useRouter()

const props = defineProps({
  id: {
    type: Number,
    required: true
  }
})
const QUERY_KEY = 'book'

const isFetchingBuy = ref(false)

const { data: book } = useQuery<Nullable<Book>>(
  QUERY_KEY,
  async () => (await useResource<Book>('books').show(props.id)).data,
  {
    placeholderData: useBooksStore().findByID(+props.id),
    onError(error: AxiosError) {
      if (error.response?.status === 404) {
        router.replace(CHUNKS.notFound)
      }
    },
    retry: 1
  }
)
const bookQueryClient = useQueryClient()

const inEditingMode = ref(false)

const updateBook = (bookData: Partial<BookBibliography>) => {
  bookQueryClient.setQueryData(QUERY_KEY, {
    ...bookQueryClient.getQueryData<BookBibliography>(QUERY_KEY),
    ...bookData
  })
  inEditingMode.value = false
}

const buyBook = () => {
  isFetchingBuy.value = true
  api
    .post(`/books/${props.id}/buy`)
    .then((response) => {
      updateBook(response.data)
    })
    .finally(() => {
      isFetchingBuy.value = false
    })
}
</script>
<template>
  <template v-if="book">
    <book-page-template>
      <template #title>
        {{ book.title }}
      </template>
      <template #image>
        <q-img :src="`${VITE_STORAGE_URL}${book?.titleThumbnail}`" placeholder-src="" />
      </template>
      <template #yearAndPublisher>
        <span v-if="book.publisher">
          {{ book.publisher.name }}
        </span>
        {{ book.year }}
      </template>
      <template #downloads>
        <a v-if="book.accessible" :href="`${VITE_API_URL}/books/${book.id}/download`" download
          >download</a
        >
        <q-btn v-else :disable="isFetchingBuy" :loading="isFetchingBuy" flat @click="buyBook"
          >buy ({{ book.price }})</q-btn
        >
        <span class="block"> downloaded by {{ book.downloads_count }} users </span>
      </template>
      <template #actions>
        <q-btn v-if="book.owned" icon="edit" flat @click="inEditingMode = true"> edit </q-btn>
      </template>
      <template #comments>
        <comments-thread :subject="book" />
      </template>
    </book-page-template>
    <q-dialog v-model="inEditingMode" no-backdrop-dismiss>
      <div class="bg-white q-pa-lg flex justify-center book-update__dialog">
        <book-form :model-value="book" class="full-width" @update:model-value="updateBook" />
      </div>
    </q-dialog>
  </template>
</template>
<style scoped lang="scss">
.book-update__dialog {
  width: 50vw;
}
</style>
