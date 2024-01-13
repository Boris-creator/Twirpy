<script setup lang="ts">
import { computed, type PropType, ref, watch } from 'vue'
import { debounce } from 'quasar'

type Option = string | number | Record<any, any> | null

const props = defineProps({
  modelValue: {
    type: [String, Number, Object, null] as PropType<Option | null>,
    required: true
  },
  options: {
    type: Array as PropType<Array<Record<string, any>>>,
    default: () => []
  },
  optionLabel: {
    type: [String, Function],
    default: 'label'
  },
  labelToOption: {
    type: Function as PropType<(value: string) => Option>,
    default: (value: string) => value
  },
  inputProps: {
    type: Object,
    default: () => ({})
  },
  loading: {
    type: Boolean,
    default: false
  },
  noOptionsText: {
    type: String,
    default: 'create new from input'
  }
})

const emits = defineEmits<{
  'update:modelValue': [value: unknown]
  'update:query': [value: string]
}>()

const getOptionLabel = (option: Option) => {
  if ((option ?? null) === null) {
    return null
  }
  if (typeof props.optionLabel === 'function') {
    return props.optionLabel(option)
  }
  return (option as Record<string, any>)[props.optionLabel]
}

const isOpen = ref(false)
const query = ref<string>(getOptionLabel(props.modelValue))
const innerValue = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emits('update:modelValue', value)
  }
})

const emitDebounced = debounce((inputValue: string) => {
  emits('update:query', inputValue)
}, 500)
const onInput = (inputValue: string) => {
  isOpen.value = true
  emitDebounced(inputValue)
}
const createOption = () => {
  innerValue.value = props.labelToOption(query.value)
}

watch(innerValue, (value) => {
  query.value = getOptionLabel(value)
})
</script>
<template>
  <q-input
    v-bind="inputProps"
    v-model.trim="query"
    @update:modelValue="onInput"
    @focus="isOpen = true"
    bottom-slots
  />
  <q-card v-show="isOpen" class="absolute full-width bg-white z-top">
    <slot name="options">
      <slot v-if="loading">
        <q-spinner size="2em" class="block q-mx-auto q-my-lg" />
      </slot>
      <q-list v-else>
        <q-item
          v-for="(option, i) of options"
          :key="i"
          clickable
          @click="
            () => {
              innerValue = option
              isOpen = false
            }
          "
        >
          <q-item-label>
            {{ getOptionLabel(option) }}
          </q-item-label>
        </q-item>
        <q-item
          v-if="!options.length"
          clickable
          @click="
            () => {
              createOption()
              isOpen = false
            }
          "
        >
          <q-item-label>{{ noOptionsText }}</q-item-label>
        </q-item>
      </q-list>
    </slot>
  </q-card>
</template>
