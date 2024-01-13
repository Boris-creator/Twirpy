<script setup lang="ts">
import { computed, type PropType } from 'vue'
import type { Book, BookBibliography } from '@/types/Book'
import type { Nullable, NullableFields } from '@/types/utils'
import { api, useResource } from '@/axios'
import { useField, useForm } from 'vee-validate'
import * as zod from 'zod'
import { toTypedSchema } from '@vee-validate/zod'
import type { AxiosResponse } from 'axios'
import BibliographyForm from '@/components/Books/Form/BibliographyForm.vue'

type BookEntity = NullableFields<
  Book & {
    file: File
  }
>

const MAX_FILE_SIZE = 800 * 10 ** 5

const props = defineProps({
  modelValue: {
    type: Object as PropType<Nullable<BookBibliography>>,
    default: () => null
  }
})
const emits = defineEmits<{
  'update:modelValue': [value: Book]
}>()

const inUpdatingMode = computed(() => !!props.modelValue?.id)

const bookToUpload: NullableFields<
  BookBibliography & {
    file: File
  }
> = {
  authors: [],
  title: null,
  year: null,
  publisher: null,
  isbn: null,
  file: null,
  id: null,
  titleThumbnail: null
}

const book = props.modelValue ?? bookToUpload

const { handleSubmit, setErrors } = useForm<
  NullableFields<
    Book & {
      file: File
    }
  >
>({
  initialValues: book
})
const {
  value: file,
  errors: fileErrors,
  errorMessage: fileErrorMessage
} = useField(
  'file',
  toTypedSchema(
    zod
      .any()
      .refine(
        (file: Nullable<File>) => inUpdatingMode.value || (file && file.size <= MAX_FILE_SIZE),
        ''
      )
      .refine(
        (file: Nullable<File>) =>
          inUpdatingMode.value || (file && ['application/pdf'].includes(file.type)),
        ''
      )
  )
)

const { update } = useResource<BookBibliography>('books')
const uploadBook = handleSubmit(async (value: BookEntity) => {
  const bookData = { ...value, file: file.value }
  new Promise<AxiosResponse>((resolve, reject) => {
    if (inUpdatingMode.value) {
      update(props.modelValue?.id as number, bookData)
        .then(resolve)
        .catch(reject)
    } else {
      api.postForm('/books', bookData).then(resolve).catch(reject)
    }
  })
    .then((response) => {
      emits('update:modelValue', response.data)
    })
    .catch((error) => {
      setErrors(error.response?.data.errors ?? {})
    })
})
</script>

<template>
  <q-form class="book-form" @submit.prevent="uploadBook">
    <bibliography-form :model-value="book" />
    <q-file
      v-model="file"
      :error="!!fileErrors.length"
      :error-message="fileErrorMessage"
      :disable="inUpdatingMode"
      :label="$t('books.form.file.label')"
      class="q-mt-md"
    />
    <q-btn type="submit" :label="$t('common.save')" class="q-mt-md" />
  </q-form>
</template>
<style scoped lang="scss">
.book-form {
  max-width: 500px;
}
</style>
