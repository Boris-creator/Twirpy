<script setup lang="ts">
import type { PropType } from 'vue'
import type { FieldContext } from 'vee-validate'

type ModelModifiers = {
  noEmptyString: boolean
}

const props = defineProps({
  value: {
    type: Object as PropType<FieldContext<string | number | null>>,
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

const onInput = (v: string | number | null) => {
  if (typeof v === 'string' && props.modelModifiers?.noEmptyString) {
    return v?.trim().length ? v : null
  }
  return v as unknown
}
</script>
<template>
  <q-input
    v-bind="$attrs"
    :model-value="value"
    :error="!!errors"
    :error-message="errors ?? ''"
    bottom-slots
    outlined
  >
    <template #error>
      <slot name="error">
        <div />
      </slot>
    </template>
  </q-input>
</template>
