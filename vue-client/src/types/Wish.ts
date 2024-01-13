import type { BookBibliography } from '@/types/Book'

export type Wish = BookBibliography & { price: number } & { user_id?: number }
