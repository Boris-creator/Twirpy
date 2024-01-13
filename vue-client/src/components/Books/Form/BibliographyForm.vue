<script setup lang="ts">
import { onBeforeMount, type PropType, ref } from 'vue'
import { api } from '@/axios'
import { useField } from 'vee-validate'
import * as zod from 'zod'
import { toTypedSchema } from '@vee-validate/zod'
import type { Book, BookBibliography } from '@/types/Book'
import type { NullableFields } from '@/types/utils'
import AutoSuggest from '@/components/common/AutoSuggest.vue'

defineProps({
  modelValue: {
    type: Object as PropType<NullableFields<BookBibliography>>,
    required: true
  }
})

const {
  value: title,
  errors: titleErrors,
  errorMessage: titleErrorMessage
} = useField('title', toTypedSchema(zod.string().min(1)))
const {
  value: isbn,
  errors: isbnErrors,
  errorMessage: isbnErrorMessage
} = useField(
  'isbn',
  toTypedSchema(
    zod
      .string()
      .regex(/^\S{10}(\S{3})?$/)
      .or(zod.null())
  )
)
const {
  value: publisher,
  errors: publisherErrors,
  errorMessage: publisherErrorMessage
} = useField(
  'publisher',
  toTypedSchema(
    zod
      .object({
        id: zod.number().or(zod.null()),
        name: zod.string().min(2)
      })
      .or(zod.null())
  )
)

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

onBeforeMount(() => {
  fetchPublishers(null)
})
</script>

<template>
  <q-input
    v-model="title"
    :label="$t('books.form.title.label')"
    :error="!!titleErrors.length"
    :error-message="titleErrorMessage"
    bottom-slots
    no-error-icon
    outlined
    class="q-mt-md"
  >
  </q-input>
  <q-input
    v-model="isbn"
    :label="$t('books.form.isbn.label')"
    :error="!!isbnErrors.length"
    :error-message="isbnErrorMessage"
    bottom-slots
    no-error-icon
    outlined
    class="q-mt-md"
  />
  <auto-suggest
    v-model="publisher"
    :options="publisherOptions"
    option-label="name"
    :label-to-option="(name: string) => ({ name, id: null })"
    :input-props="{
      label: 'Publishers',
      error: !!publisherErrors.length,
      errorMessage: publisherErrorMessage
    }"
    :loading="isFetchingPublishers"
    @update:query="fetchPublishers"
  />
  <slot name="additionalFields" />
</template>
