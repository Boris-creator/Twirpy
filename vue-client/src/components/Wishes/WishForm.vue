<script setup lang="ts">
import { computed, type PropType } from 'vue'
import { useResource } from '@/axios'
import { useField, useForm } from 'vee-validate'
import * as zod from 'zod'
import { toTypedSchema } from '@vee-validate/zod'
import BibliographyForm from '@/components/Books/Form/BibliographyForm.vue'
import type { Wish } from '@/types/Wish'
import type { Book } from '@/types/Book'
import type { Nullable, NullableFields } from '@/types/utils'
import type { AxiosResponse } from 'axios'

const props = defineProps({
  modelValue: {
    type: Object as PropType<Nullable<Wish>>,
    default: () => null
  }
})
const emits = defineEmits<{
  'update:modelValue': [value: Book]
}>()

const inUpdatingMode = computed(() => !!props.modelValue?.id)

const defaultWish: NullableFields<Wish> = {
  authors: [],
  title: null,
  year: null,
  publisher: null,
  isbn: null,
  id: null,
  titleThumbnail: null,
  price: null
}

const wish = props.modelValue ?? defaultWish

const { handleSubmit, setErrors } = useForm<NullableFields<Wish>>({
  initialValues: wish
})

const { value: price, errors } = useField('price', toTypedSchema(zod.number().min(0)))

const { update, create } = useResource<NullableFields<Wish>>('wishes')
const onSubmit = handleSubmit(async (value: NullableFields<Wish>) => {
  new Promise<AxiosResponse>((resolve, reject) => {
    if (inUpdatingMode.value) {
      update(props.modelValue?.id as number, value)
        .then(resolve)
        .catch(reject)
    } else {
      create(value).then(resolve).catch(reject)
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
  <q-form class="book-form" @submit.prevent="onSubmit">
    <bibliography-form :model-value="wish" />
    <q-input
      v-model.number="price"
      label="Rewards"
      :error="!!errors.length"
      :error-message="errors[0]"
      bottom-slots
      no-error-icon
      outlined
      class="q-mt-md"
    />
    <q-btn type="submit" label="save" class="q-mt-md" />
  </q-form>
</template>
<style scoped lang="scss">
.book-form {
  max-width: 500px;
}
</style>
