import { ref } from 'vue'
import { defineStore } from 'pinia'
import type { Book } from '@/types/Book'

export const useBooksStore = defineStore('bookStore', () => {
  const books = ref<Array<Book>>([])
  const findByID = (bookId: number) => books.value.find(({ id }) => id === bookId) ?? null

  return { books, findByID }
})
