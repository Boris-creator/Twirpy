<script setup lang="ts">
import { onBeforeMount } from 'vue'
import { api } from '@/axios'
import StickyMenu from '@/components/StickyMenu.vue'

onBeforeMount(async () => {
  await api.post('/login', {
    email: 'test@gmail.com',
    password: 'qwerty'
  })
})
</script>

<template>
  <q-layout view="hHh lpR fFf">
    <q-page-container>
      <q-page class="flex items-center justify-center">
        <router-view />
      </q-page>
      <sticky-menu />
    </q-page-container>
  </q-layout>
</template>
