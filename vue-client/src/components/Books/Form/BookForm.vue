<script setup lang="ts">
import { onBeforeMount, ref } from 'vue'
import type { Book, BookBibliography } from '@/types/Book'
import type { Nullable, NullableFields } from '@/types/utils'
import { api } from '@/axios'
import { useForm } from 'vee-validate'
import * as zod from 'zod'
import { toTypedSchema } from '@vee-validate/zod'
import FormInput from '@/components/Books/Form/FormInput.vue'
import AutoSuggest from '@/components/Books/Form/AutoSuggest.vue'

const MAX_FILE_SIZE = 800 * 10 ** 5

const bookToUpload: NullableFields<
  BookBibliography & {
    file: File
  }
> = {
  authors: [],
  title: null,
  year: null,
  publishedBy: null,
  isbn: null,
  file: null,
  id: null,
  titleThumbnail: null
}

const { defineInputBinds, handleSubmit, errors, setErrors } = useForm<
  NullableFields<
    Book & {
      file: File
    }
  >
>({
  validationSchema: toTypedSchema(
    zod.object({
      title: zod.string().nonempty(),
      publishedBy: zod
        .object({
          id: zod.number().or(zod.null()),
          name: zod.string().min(2)
        })
        .or(zod.null()),
      isbn: zod
        .string()
        .regex(/^\S{10}(\S{3})?$/)
        .or(zod.null()),
      file: zod
        .any()
        .refine((file: Nullable<File>) => file && file.size <= MAX_FILE_SIZE, '')
        .refine((file: Nullable<File>) => file && ['application/pdf'].includes(file.type), '')
    })
  ),
  initialValues: bookToUpload
})
const title = defineInputBinds('title', { validateOnInput: true })
const isbn = defineInputBinds('isbn')
const file = defineInputBinds('file')
const publishedBy = defineInputBinds('publishedBy')

const publisherOptions = ref<Array<{ value: Book['publishedBy']; label: string }>>([])

const isFetchingPublishers = ref(false)
const fetchPublishers = (query: string | null) => {
  isFetchingPublishers.value = true
  api
    .get(`/publishers?search=${query ?? ''}`)
    .then((response) => {
      publisherOptions.value = response.data
    })
    .finally(() => {
      isFetchingPublishers.value = false
    })
}

const uploadBook = handleSubmit(async (values) => {
  api.postForm('/books', values).catch((error) => {
    setErrors(error.response?.data.errors)
  })
})

onBeforeMount(() => {
  fetchPublishers(null)
})
</script>

<template>
  <q-form @submit.prevent="uploadBook">
    <form-input :value="title" :errors="errors.title" label="Book title" autofocus outlined />
    <!--<q-select
      :model-value="publishedBy.value"
      :options="publisherOptions"
      option-label="name"
      @update:model-value="publishedBy.onInput"
    />-->
    <auto-suggest
      :model-value="publishedBy.value"
      :options="publisherOptions"
      option-label="name"
      :label-to-option="(name: string) => ({ name, id: null })"
      :input-props="{ label: 'Publishers', errors: errors.publishedBy }"
      :loading="isFetchingPublishers"
      @update:model-value="publishedBy.onInput"
      @update:query="fetchPublishers"
    />
    <form-input
      :value="isbn"
      :errors="errors.isbn"
      label="ISBN"
      outlined
      clearable
      class="q-mt-md"
    />
    <q-file
      v-model="file.value"
      :error="!!errors.file"
      :error-message="errors.file"
      label="Select book"
      class="q-mt-md"
      @update:model-value="file.onInput"
    />
    <q-btn type="submit" label="upload" class="q-mt-md" />
  </q-form>
</template>
