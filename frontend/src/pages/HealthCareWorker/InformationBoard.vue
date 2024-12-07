<template>
  <q-page class="q-pa-md">
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h1 class="text-dark text-3xl font-bold">Information Board</h1>
      </q-toolbar-title>
    </q-toolbar>

    <div class="q-gutter-md">
      <q-carousel v-model="slide" transition-prev="jump-right" transition-next="jump-left" swipeable animated
        control-color="dark" prev-icon="arrow_left" next-icon="arrow_right" navigation padding arrows
        class="bg-white text-dark shadow-1 rounded-borders" height="750px">
        <q-carousel-slide v-for="(board, index) in informationBoards" :key="board.id" :name="`board-${index}`"
          class="column no-wrap flex-center">
          <div class="q-mt-md text-center">
            <img v-if="board.files && board.files.length > 0" :src="getMediaURL(board.files[0])" alt="Image" />
            <div v-else>No image available</div>
          </div>
        </q-carousel-slide>
      </q-carousel>
    </div>
  </q-page>
</template>

<script>
import informationBoardService from 'src/services/informationBoardService'
import handleMedia from 'src/utils/mixin/handleMedia';

export default {
  mixins: [handleMedia],
  data() {
    return {
      slide: '',
      informationBoards: []
    }
  },
  methods: {
    async fetchInformationBoards() {
      try {
        const response = await informationBoardService.getAllInformationBoards()
        this.informationBoards = response.data.body

        if (this.informationBoards.length > 0) {
          this.slide = `board-0`
        }
      } catch (error) {
        console.error('Error fetching information boards:', error)
      }
    }
  },
  mounted() {
    this.fetchInformationBoards()
  }
}
</script>
