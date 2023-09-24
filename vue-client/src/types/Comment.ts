import type { Book } from '@/types/Book'
import type { Nullable } from '@/types/utils'

type CommentMetaData = {
  owned: boolean
  edited: boolean
  answers_count: number
}

export type Comment = {
  id: number
  text: string
  author: unknown
  subject: Book
  answerTo?: Nullable<Comment>
}

export type ThreadComment = Comment & CommentMetaData
