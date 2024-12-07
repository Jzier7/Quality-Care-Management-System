<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h1 class="text-primary">Information Board</h1>
        </q-toolbar-title>
      </q-toolbar>

      <div class="row justify-between items-center">
        <q-btn dense label="Add Information Board" color="primary" @click="openAddInformationBoardModal"
          style="text-transform: capitalize;" />
        <form @submit.prevent="fetchInformationBoards" class="q-gutter-md">
          <q-input rounded outlined dense v-model="search" placeholder="Search Information Boards"
            @input="fetchInformationBoards" color="primary">
            <template v-slot:prepend>
              <q-icon name="search" />
            </template>
          </q-input>
        </form>
      </div>

      <div class="row cards-container">
        <div v-for="board in informationBoards" :key="board.id" class="col-12 col-md-3 card-item">
          <q-card>
            <div v-if="board.files" class="q-mb-md">
              <img :src="getMediaURL(board.files[0])" alt="Image" class="card-image" />
            </div>

            <q-card-section>
              <div class="text-weight-bold">{{ board.name }}</div>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn flat icon="delete" color="red" @click="openDeleteInformationBoardModal(board)" />
            </q-card-actions>
          </q-card>
        </div>
      </div>

      <!-- Pagination Section -->
      <div class="row justify-center q-mt-md">
        <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
      </div>

      <AddInformationBoardModal :fetchInformationBoards="fetchInformationBoards" />
      <DeleteInformationBoardModal :fetchInformationBoards="fetchInformationBoards"
        :deleteData="informationBoardData" />
    </div>
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import informationBoardService from 'src/services/informationBoardService';
import handleMedia from 'src/utils/mixin/handleMedia';

export default {
  components: {
    AddInformationBoardModal: defineAsyncComponent(() => import('components/Modals/InformationBoard/AddInformationBoard.vue')),
    DeleteInformationBoardModal: defineAsyncComponent(() => import('components/Modals/InformationBoard/DeleteInformationBoard.vue')),
  },
  mixins: [handleMedia],
  data() {
    return {
      informationBoardData: {},
      informationBoards: [],
      search: '',
      currentPage: 1,
      pageSize: 5,
      lastPage: 1,
      total: 0,
    };
  },
  watch: {
    search() {
      this.currentPage = 1;

      clearTimeout(this.debounceTimeout);

      this.debounceTimeout = setTimeout(() => {
        this.fetchInformationBoards();
      }, 1000);
    },
  },
  methods: {
    openAddInformationBoardModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddInformationBoardModal(true);
    },
    openDeleteInformationBoardModal(board) {
      this.informationBoardData = board;
      const modalStore = useModalStore();
      modalStore.setShowDeleteInformationBoardModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchInformationBoards();
    },
    async fetchInformationBoards() {
      try {
        const response = await informationBoardService.getPaginatedInformationBoards({
          page: this.currentPage,
          pageSize: this.pageSize,
          search: this.search,
        });

        this.informationBoards = response.data.body || [];
        this.total = response.data.details.total || 0;
        this.lastPage = Math.ceil(this.total / this.pageSize);
      } catch (error) {
        console.error('Error fetching information boards:', error);
      }
    },
  },
  mounted() {
    this.fetchInformationBoards();
  },
};
</script>

<style scoped>
.card-item {
  padding: 10px 5px 0;
}

.card-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}
</style>
