<script setup lang="ts">
import { useQuasar } from 'quasar'
import { computed, ref } from 'vue'

const isSm = computed(() => useQuasar().screen.sm || useQuasar().screen.xs)

const isSliderOpen = ref(false)
</script>
<template>
  <template v-if="isSm">
    <div class="full-width">
      <h1 class="text-h3">
        <slot name="title" />
      </h1>
      <div>
        <slot name="image" />
      </div>
      <div class="q-px-md">
        <h3 v-if="$slots.yearAndPublisher"><slot name="yearAndPublisher" /></h3>
        <div class="text-h4">
          <slot name="downloads" />
        </div>
      </div>
      <div class="q-mt-lg flex justify-center text-h4">
        <q-btn-group>
          <q-btn label="comments" icon="group" @click="isSliderOpen = !isSliderOpen" />
          <slot name="actions" />
        </q-btn-group>
      </div>
    </div>
  </template>
  <template v-else>
    <div class="full-width row">
      <div class="col-5 col-lg-3">
        <slot name="image" />
      </div>
      <div class="col-7 q-px-md">
        <h1>
          <slot name="title" />
        </h1>
        <h3 v-if="$slots.yearAndPublisher"><slot name="yearAndPublisher" /></h3>
        <div class="text-h4">
          <slot name="downloads" />
        </div>
        <div class="q-mt-lg text-h4">
          <q-btn-group>
            <q-btn label="comments" icon="group" @click="isSliderOpen = !isSliderOpen" />
            <slot name="actions" />
          </q-btn-group>
        </div>
      </div>
    </div>
  </template>
  <q-drawer v-model="isSliderOpen" overlay side="right" :width="500">
    <slot name="comments" v-if="isSliderOpen" />
  </q-drawer>
</template>
