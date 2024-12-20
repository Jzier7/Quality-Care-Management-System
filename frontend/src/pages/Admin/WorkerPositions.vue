<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">Healthcare Worker Positions</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none">
      <div class="q-mb-md q-gutter-sm flex items-center">
        <q-btn label="Add Position" color="primary" @click="openAddModal" class="q-mr-sm" />
        <q-space />
        <q-input rounded outlined dense color="primary" v-model="search" placeholder="Search by name" class="q-mr-sm">
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </div>

      <q-table flat bordered :rows="positions" :columns="columns" row-key="id" :pagination="{ rowsPerPage: pageSize }"
        hide-bottom>
        <template v-slot:header="props">
          <q-tr :props="props">
            <q-th v-for="col in props.cols" :key="col.name" :props="props" class="text-primary text-bold">
              {{ col.label }}
            </q-th>
          </q-tr>
        </template>

        <template v-slot:body-cell-actions="props">
          <q-td :props="props" align="center">
            <q-btn flat dense icon="edit" color="primary" @click="openEditModal(props.row)" />
            <q-btn flat dense icon="delete" color="negative" @click="openDeleteModal(props.row)" />
          </q-td>
        </template>
      </q-table>

      <div class="row justify-center q-mt-md">
        <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
      </div>
    </div>

    <AddPositionModal :fetchPositions="fetchPositions" />
    <EditPositionModal :fetchPositions="fetchPositions" :editData="positionData" />
    <DeletePositionModal :fetchPositions="fetchPositions" :deleteData="positionData" />
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import positionService from 'src/services/positionService';

export default {
  components: {
    AddPositionModal: defineAsyncComponent(() => import('components/Modals/Position/AddPosition.vue')),
    EditPositionModal: defineAsyncComponent(() => import('components/Modals/Position/EditPosition.vue')),
    DeletePositionModal: defineAsyncComponent(() => import('components/Modals/Position/DeletePosition.vue')),
  },
  data() {
    return {
      positions: [],
      positionData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      debounceTimeout: null,
      columns: [
        { name: 'name', label: 'Position Name', align: 'center', field: 'name' },
        { name: 'description', label: 'Description', align: 'center', field: 'description' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  watch: {
    search() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchPositions();
      }, 1000);
    },
  },
  mounted() {
    this.fetchPositions();
  },
  methods: {
    openAddModal(addData) {
      this.positionData = addData;

      const modalStore = useModalStore();
      modalStore.setShowAddPositionModal(true);
    },
    openEditModal(editData) {
      this.positionData = editData;

      const modalStore = useModalStore();
      modalStore.setShowEditPositionModal(true);
    },
    openDeleteModal(deleteData) {
      this.positionData = deleteData;

      const modalStore = useModalStore();
      modalStore.setShowDeletePositionModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchPositions();
    },
    async fetchPositions() {
      try {
        const response = await positionService.getPaginatedPositions({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
        });
        this.positions = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
        this.total = response.data.details.total || 0;
      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.text-bold {
  font-weight: bold;
}

.q-pagination .q-btn {
  background-color: $primary;
  color: white;
}
</style>
