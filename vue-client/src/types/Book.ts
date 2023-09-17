export type Author = {
  name: string
}
export type BookBibliography = {
  authors: Array<Author>
  title: string
  year: number | string
  publishedBy: {
    id: number | null
    name: string
  }
  isbn: string
  id: number
  titleThumbnail: string
}

export type Book = BookBibliography & {
  ownerId: number
  accessible: boolean
  downloads_count: number
}
