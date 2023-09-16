export type Author = {
  name: string
}

export type Book = {
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
} & {
  ownerId: number
  accessible: boolean
  downloads_count: number
}
