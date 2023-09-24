<script setup lang="ts">
import type { PropType } from 'vue'
import type { Nullable, NullableFields } from '@/types/utils'
import type { Book } from '@/types/Book'
import type { Comment } from '@/types/Comment'
import { useForm } from 'vee-validate'
import * as zod from 'zod'
import { toTypedSchema } from '@vee-validate/zod'
import FormInput from '@/components/common/FormInput.vue'
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
  (e: 'update:modelValue', value: Comment): void
  (e: 'deselect'): void
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

const { values, errors, handleSubmit, setValues, resetForm, defineInputBinds } = useForm<
  NullableFields<Comment>
>({
  validationSchema: toTypedSchema(
    zod.object({
      text: zod.string().nonempty().min(3)
    })
  ),
  initialValues: props.modelValue ? props.modelValue : defaultComment()
})

const commentText = defineInputBinds('text', { validateOnBlur: false })

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
      resetForm()
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
        <form-input type="textarea" :value="commentText" :errors="errors.text" />
      </div>
      <q-btn type="submit" :disable="disabled" icon="send" class="block q-ml-auto" />
    </q-form>
  </div>
</template>
