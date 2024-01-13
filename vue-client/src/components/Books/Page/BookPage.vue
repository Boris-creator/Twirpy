<script setup lang="ts">
import { ref, watchEffect } from 'vue'
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
import WishesList from '@/components/Wishes/WishesList.vue'
import type { Wish } from '@/types/Wish'

const router = useRouter()

const props = defineProps({
  id: {
    type: Number,
    required: true
  }
})
const QUERY_KEY = 'book'
const WISHES_QUERY_KEY = 'wishes'

const isFetchingBuy = ref(false)
const isWishSearchOpen = ref(false)
const inEditingMode = ref(false)

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

const { data: wishes, refetch } = useQuery<Array<any>>(
  WISHES_QUERY_KEY,
  async () => (await useResource<any>('wishes').search({ bookId: props.id })).data,
  { enabled: false }
)

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
const offerBook = (wish: Wish) => {
  api.post('/offers/new', { book_id: props.id, wish_id: wish.id })
}

watchEffect(() => {
  if (isWishSearchOpen.value) {
    refetch.value()
  }
})
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
      <template v-if="book.owned" #actions>
        <q-btn icon="edit" flat @click="inEditingMode = true"> edit </q-btn>
        <q-btn icon="paid" flat @click="isWishSearchOpen = true"> wishes </q-btn>
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
    <q-drawer v-model="isWishSearchOpen" overlay side="right" :width="500">
      <wishes-list :wishes="wishes">
        <template #actions="{ wish }">
          <q-btn @click="offerBook(wish)">offer</q-btn>
        </template>
      </wishes-list>
    </q-drawer>
  </template>
</template>
<style scoped lang="scss">
.book-update__dialog {
  width: 50vw;
}
</style>
