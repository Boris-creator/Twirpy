<script setup lang="ts">
import { computed, onBeforeMount, type PropType, ref } from 'vue'
import type { Book, BookBibliography } from '@/types/Book'
import type { Nullable, NullableFields } from '@/types/utils'
import { api, useResource } from '@/axios'
import { useForm } from 'vee-validate'
import * as zod from 'zod'
import { toTypedSchema } from '@vee-validate/zod'
import FormInput from '@/components/common/FormInput.vue'
import AutoSuggest from '@/components/common/AutoSuggest.vue'
import type { AxiosResponse } from 'axios'

const MAX_FILE_SIZE = 800 * 10 ** 5

const props = defineProps({
  modelValue: {
    type: Object as PropType<Nullable<BookBibliography>>,
    default: () => null
  }
})
const emits = defineEmits<{
  (e: 'update:modelValue', value: Book): void
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
      publisher: zod
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
        .refine(
          (file: Nullable<File>) => inUpdatingMode.value || (file && file.size <= MAX_FILE_SIZE),
          ''
        )
        .refine(
          (file: Nullable<File>) =>
            inUpdatingMode.value || (file && ['application/pdf'].includes(file.type)),
          ''
        )
    })
  ),
  initialValues: props.modelValue ?? bookToUpload
})
const title = defineInputBinds('title', { validateOnInput: true })
const isbn = defineInputBinds('isbn')
const file = defineInputBinds('file')
const publisher = defineInputBinds('publisher')

const publisherOptions = ref<Array<{ value: Book['publisher']; label: string }>>([])

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

const { update } = useResource<BookBibliography>('books')
const uploadBook = handleSubmit(async (values) => {
  new Promise<AxiosResponse>((resolve, reject) => {
    if (inUpdatingMode.value) {
      update(props.modelValue?.id as number, values)
        .then(resolve)
        .catch(reject)
    } else {
      api.postForm('/books', values).then(resolve).catch(reject)
    }
  })
    .then((response) => {
      emits('update:modelValue', response.data)
    })
    .catch((error) => {
      setErrors(error.response?.data.errors ?? {})
    })
})

onBeforeMount(() => {
  fetchPublishers(null)
})
</script>

<template>
  <q-form @submit.prevent="uploadBook" class="book-form">
    <form-input :value="title" :errors="errors.title" label="Book title" autofocus outlined />
    <!--<q-select
      :model-value="publisher.value"
      :options="publisherOptions"
      option-label="name"
      @update:model-value="publisher.onInput"
    />-->
    <auto-suggest
      :model-value="publisher.value"
      :options="publisherOptions"
      option-label="name"
      :label-to-option="(name: string) => ({ name, id: null })"
      :input-props="{ label: 'Publishers', errors: errors.publisher }"
      :loading="isFetchingPublishers"
      @update:model-value="publisher.onInput"
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
      :disable="inUpdatingMode"
      label="Select book"
      class="q-mt-md"
      @update:model-value="file.onInput"
    />
    <q-btn type="submit" label="save" class="q-mt-md" />
  </q-form>
</template>
<style scoped lang="scss">
.book-form {
  max-width: 500px;
}
</style>
