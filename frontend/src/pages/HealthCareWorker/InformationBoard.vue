<template>
  <q-page class="q-pa-md">
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h1 class="text-dark text-3xl font-bold">Information Board</h1>
      </q-toolbar-title>
    </q-toolbar>

    <div class="q-gutter-md">
      <q-carousel
        v-model="slide"
        transition-prev="jump-right"
        transition-next="jump-left"
        swipeable
        animated
        control-color="dark"
        prev-icon="arrow_left"
        next-icon="arrow_right"
        navigation
        padding
        arrows
        class="bg-white text-dark shadow-1 rounded-borders"
        height="750px"
      >
        <q-carousel-slide name="style" class="column no-wrap flex-center">
          <div class="q-mt-md text-center">
            <img src="~/assets/image.jpeg" alt="">
          </div>
        </q-carousel-slide>
        <q-carousel-slide name="tv" class="column no-wrap flex-center">
          <div class="q-mt-md text-center">
            <img src="~/assets/image2.jpeg" alt="">
          </div>
        </q-carousel-slide>
        <q-carousel-slide name="layers" class="column no-wrap flex-center">
          <div class="q-mt-md text-center">
            <img src="~/assets/image3.jpeg" alt="">
          </div>
        </q-carousel-slide>
      </q-carousel>
    </div>
  </q-page>
</template>

<script>
import { ref } from 'vue'

export default {
  setup () {
    return {
      slide: ref('style'),
    }
  }
}
</script>

