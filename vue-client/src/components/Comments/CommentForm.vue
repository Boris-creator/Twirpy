<script setup lang="ts">
import type { PropType } from 'vue'
import type { Nullable, NullableFields } from '@/types/utils'
import type { Book } from '@/types/Book'
import type { Comment } from '@/types/Comment'
import { useField, useForm } from 'vee-validate'
import * as zod from 'zod'
import { toTypedSchema } from '@vee-validate/zod'
import { computed, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object as PropType<Nullable<Comment>>,
    default: () => null
  },
  subject: {
    type: Object as PropType<Pick<Book, 'id'>>,
    required: true
  },
  answerTo: {
    type: Object as PropType<Nullable<Comment>>,
    default: () => null
  },
  disabled: {
    type: Boolean,
    default: false
  }
})
const emits = defineEmits<{
  'update:modelValue': [value: NullableFields<Comment>]
  deselect: []
}>()

const defaultComment = () => ({
  text: null,
  answerTo: props.answerTo,
  subject: props.subject
})

const commentToAnswer = computed<Nullable<Comment>>({
  get() {
    return props.answerTo
  },
  set() {
    emits('deselect')
  }
})

const { values, handleSubmit, setValues } = useForm<NullableFields<Comment>>({
  initialValues: props.modelValue ? props.modelValue : defaultComment()
})

const {
  value: commentText,
  errors,
  errorMessage,
  meta,
  setTouched
} = useField<string>('text', toTypedSchema(zod.string().min(3)))

const saveComment = handleSubmit(() => {
  emits('update:modelValue', { ...values, answerTo: commentToAnswer.value })
})

watch(
  () => props.modelValue,
  (value) => {
    if (value) {
      setValues(value)
    } else {
      setValues(defaultComment())
      setTouched(false)
    }
  }
)
</script>
<template>
  <div>
    <div v-if="commentToAnswer" class="row full-width">
      <p>{{ commentToAnswer.text }}</p>
      <q-btn flat icon="close" class="q-ml-auto" @click="emits('deselect')" />
    </div>
    <q-form class="full-width q-py-lg" @submit.prevent="saveComment">
      <div>
        <q-input
          type="textarea"
          v-model="commentText"
          :error="!!errors.length && meta.touched"
          :error-message="errorMessage"
          bottom-slots
          outlined
        />
      </div>
      <q-btn type="submit" :disable="disabled" icon="send" class="block q-ml-auto" />
    </q-form>
  </div>
</template>
