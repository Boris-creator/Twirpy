<script setup lang="ts"></script>

<template>
  <main class="main full-width window-height column">
    <div class="absolute-bottom col-8 bg-white row items-center justify-center">
      <router-link to="/books" class="block">
        <q-btn color="black" class="main__link text-h3 q-pa-lg"> All books </q-btn>
      </router-link>
      <router-link to="/books/add" class="block">
        <q-btn color="black" class="main__link text-h3 q-pa-lg"> Add book </q-btn>
      </router-link>
    </div>
  </main>
</template>
<style scoped lang="scss">
.main {
  @media (max-width: 576px) {
    background-image: url('@/assets/images/background-sm.png');
  }
  background-size: 100vw !important;
  background: url('@/assets/images/background-lg.png') no-repeat;
  &__link {
    width: 40vw;
    margin: 0 2vw;
    @media (max-width: 576px) {
      width: 100vw;
      margin: 0;
    }
  }
}
</style>
