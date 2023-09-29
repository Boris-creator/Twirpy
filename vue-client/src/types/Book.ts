import type { NullableFields } from '@/types/utils'

export type Author = {
  name: string
}
export type BookBibliography = {
  authors: Array<Author>
  title: string
  year: number | string
  publisher: {
    id: number | null
    name: string
  }
  isbn: string
  id: number
  titleThumbnail: string
}

export type Book = BookBibliography & {
  ownerId: number
  owned: boolean
  accessible: boolean
  downloads_count: number
}

export type BookFilter = Partial<NullableFields<Book>>
