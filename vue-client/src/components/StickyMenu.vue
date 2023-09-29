<script setup lang="ts">
import { useRouter } from 'vue-router'
import { computed } from 'vue'
import { ROUTE_NAMES, CHUNKS, pathJoin } from '@/router'

const router = useRouter()
const menuLink = computed(() => {
  switch (router.currentRoute.value.name) {
    case ROUTE_NAMES.book:
    case ROUTE_NAMES.addBook:
      return {
        icon: 'menu_book',
        to: CHUNKS.books
      }
    case ROUTE_NAMES.books:
      return {
        icon: 'add',
        to: pathJoin(CHUNKS.books, CHUNKS.addBook)
      }
    default:
      return null
  }
})
</script>
<template>
  <q-page-sticky position="bottom-right" :offset="[15, 15]">
    <router-link v-if="menuLink" :to="menuLink.to">
      <q-btn fab :icon="menuLink.icon" color="black" />
    </router-link>
  </q-page-sticky>
</template>
