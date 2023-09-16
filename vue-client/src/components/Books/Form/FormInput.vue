<script setup lang="ts">
import type { PropType } from 'vue'
import type { BaseInputBinds } from 'vee-validate'

type ModelModifiers = {
  noEmptyString: boolean
}

const props = defineProps({
  value: {
    type: Object as PropType<BaseInputBinds<any>>,
    required: true
  },
  errors: {
    type: String as PropType<string | null>,
    default: null
  },
  modelModifiers: {
    type: Object as PropType<ModelModifiers>,
    default: () => ({ noEmptyString: true })
  }
})

const onInput = (v: string | null) => {
  if (props.modelModifiers?.noEmptyString) {
    return v?.trim().length ? v : null
  }
  return v as unknown
}
</script>
<template>
  <q-input
    v-bind="$attrs"
    :model-value="value.value"
    :error="!!errors"
    :error-message="errors"
    label="Book title"
    bottom-slots
    outlined
    @update:model-value="value.onInput(onInput($event) as Event)"
    @blur="value.onBlur"
  >
    <template #error>
      <slot name="error">
        <div />
      </slot>
    </template>
  </q-input>
</template>
