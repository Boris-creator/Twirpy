<script setup lang="ts">
import CommentForm from '@/components/Comments/CommentForm.vue'
import { useResource } from '@/axios'
import { useMutation, useQuery, useQueryClient } from 'vue-query'
import { ref, type PropType, computed } from 'vue'
import type { Book } from '@/types/Book'
import type { Comment, ThreadComment } from '@/types/Comment'
import type { Nullable, NullableFields } from '@/types/utils'
import * as l from 'lodash'

type CommentsFilter = Partial<{
  bookId: number
  answerTo: number
}>

const props = defineProps({
  subject: {
    type: Object as PropType<Book>,
    required: true
  }
})

const defaultSearchFilter = () => ({ bookId: props.subject?.id })

const currentComment = ref<Nullable<Comment>>(null)
const commentToAnswer = ref<Nullable<ThreadComment>>(null)
const searchFilter = ref<CommentsFilter>(defaultSearchFilter())

const queryKey = ['comments', searchFilter]
const createQueryKey = 'comment'
const updateQueryKey = 'updateComment'
const removeQueryKey = 'removeComment'

const client = useQueryClient()
const api = useResource<Comment>('comments')
const { data: comments } = useQuery(
  queryKey,
  async () => (await api.search(searchFilter.value)).data,
  {
    placeholderData: []
  }
)

const { mutate: addComment, isLoading: isAdding } = useMutation(
  createQueryKey,
  async () => (await api.create(currentComment.value as Comment)).data,
  {
    onSuccess(comment) {
      const comments = client.getQueryData<Array<Comment>>(queryKey) as Array<Comment>
      client.setQueryData(queryKey, [...comments, comment])
      currentComment.value = null

      if (comment.answerTo) {
        const answeredComment = l.find(comments, { id: comment.answerTo.id })
        answeredComment.answers_count++
        commentToAnswer.value = null
      }
    }
  }
)

const { mutate: updateComment, isLoading: isUpdating } = useMutation(
  updateQueryKey,
  async (comment: Comment) => (await api.update(comment.id, comment)).data,
  {
    onSuccess(comment) {
      const comments = client.getQueryData<Array<Comment>>(queryKey) as Array<Comment>
      const commentToUpdate = l.find(comments, { id: comment.id })
      Object.assign(commentToUpdate, comment)
      currentComment.value = null
    }
  }
)

const { mutate: removeComment, isLoading } = useMutation(
  removeQueryKey,
  async (comment: Comment) => (await api.destroy(comment.id)).data,
  {
    onSuccess(_, comment) {
      const comments = [...(client.getQueryData<Array<Comment>>(queryKey) as Array<Comment>)]
      l.remove(comments, ({ id }: Comment) => id === comment.id)
      client.setQueryData(queryKey, comments)
      currentComment.value = null
      if (commentToAnswer.value?.id === comment.id) {
        commentToAnswer.value = null
      }
    }
  }
)

const addOrUpdateComment = (comment: NullableFields<Comment>) => {
  if (comment.id) {
    updateComment(comment)
  } else {
    addComment()
  }
}

function searchAnswers(comment: Comment) {
  searchFilter.value.answerTo = comment.id
}
function answerTo(comment: ThreadComment) {
  commentToAnswer.value = comment
}

const isDisplayAnswers = computed(() => !!searchFilter.value.answerTo)
</script>
<template>
  <div style="overflow-x: hidden; height: 70vh">
    <div v-if="isDisplayAnswers">
      <q-btn
        flat
        label="see all"
        class="full-width"
        @click="searchFilter = defaultSearchFilter()"
      />
    </div>
    <q-chat-message
      v-for="comment of comments"
      :key="comment.id"
      :text="[comment.text]"
      :sent="comment.owned"
      :name="comment.author.name"
      size="5"
    >
      <template #stamp>
        <q-card-actions align="right">
          <q-btn-group flat>
            <q-btn
              v-if="comment.owned"
              flat
              size="sm"
              icon="edit"
              @click="currentComment = comment"
            >
              <q-tooltip>edit</q-tooltip>
            </q-btn>
            <q-btn
              v-if="comment.owned"
              flat
              size="sm"
              icon="delete"
              :disable="isLoading"
              @click="removeComment(comment)"
            >
              <q-tooltip>delete</q-tooltip>
            </q-btn>
            <q-btn flat size="sm" icon="reply" @click="answerTo(comment)">
              <q-tooltip>reply</q-tooltip>
            </q-btn>
            <q-btn flat size="sm" :label="comment.answers_count" @click="searchAnswers(comment)">
              <q-tooltip>look answers</q-tooltip>
            </q-btn>
          </q-btn-group>
        </q-card-actions>
        <span v-if="comment.edited">edited</span>
      </template>
    </q-chat-message>
    <!--<q-card v-for="comment of comments" :key="comment.id" class="q-mt-md">
      <q-card-section>
        <h5 class="text-h6 q-my-md">{{ comment.author.name }}</h5>
        <p>{{ comment.text }}</p>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn-group flat />
      </q-card-actions>
    </q-card>-->
  </div>
  <comment-form
    v-model="currentComment"
    :subject="{ id: subject.id }"
    :answer-to="commentToAnswer"
    :disabled="isAdding || isUpdating"
    class="fixed-bottom bg-white"
    @update:modelValue="addOrUpdateComment"
    @deselect="commentToAnswer = null"
  />
</template>
