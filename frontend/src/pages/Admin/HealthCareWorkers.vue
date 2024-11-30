<template>
  <q-page padding>
    <!-- Toolbar Section -->
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">Healthcare Workers</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <!-- Add Worker and Search Bar Section -->
    <div class="row justify-between items-center">
      <q-btn dense label="Add Worker" color="primary" @click="openAddWorkerModal" style="text-transform: capitalize;" />
      <form @submit.prevent="filterWorkers" class="q-gutter-md">
        <q-input rounded outlined dense v-model="search" placeholder="Search Workers" @input="filterWorkers"
          color="primary">
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </form>
    </div>

    <!-- Display Workers Section -->
    <div class="q-mt-md">
      <div v-if="workers.length === 0" class="row justify-center q-ma-lg">
        <p class="text-h6 text-gray-600">No workers found.</p>
      </div>

      <div class="q-gutter-sm">
        <q-card v-for="worker in workers" :key="worker.id" flat bordered
          class="q-pa-sm border border-gray-300 rounded-lg shadow-sm hover:shadow-lg transition-shadow">
          <q-card-section class="overflow-hidden">
            <div class="row items-center q-gutter-sm">
              <!-- Profile Picture -->
              <div class="q-pa-md">
                <q-img v-if="worker.user.profile_picture" :src="worker.user.profile_picture" alt="Worker Profile"
                  class="rounded-full" style="width: 100px; height: 100px; object-fit: cover;" />
                <q-icon v-else name="account_circle" size="100px" color="primary" class="rounded-full" />
              </div>

              <!-- Worker Information -->
              <div>
                <h2 class="font-bold text-primary text-h6">
                  {{ worker.user.last_name }},
                  {{ worker.user.first_name }}
                  <span v-if="worker.user.middle_name" class="text-body1">{{ worker.user.middle_name }}</span>
                </h2>
                <p class="text-gray-500 text-body2"><strong>LICENSE NUMBER:</strong> {{ worker.license_number }}</p>
                <p class="text-gray-500 text-body2"><strong>DEPARTMENT:</strong> {{ worker.department }}</p>
                <p class="text-gray-500 text-body2">
                  <strong>SHIFTING:</strong> {{ formatTime(worker.shift_start_time) }} - {{
                    formatTime(worker.shift_end_time) }}
                </p>
                <p class="text-gray-500 text-body2"><strong>POSITION:</strong> {{ worker.position }}</p>
              </div>
            </div>
          </q-card-section>

          <!-- Actions -->
          <q-card-actions class="row justify-end">
            <q-btn label="Edit" color="primary" @click="openEditWorkerModal(worker)" style="text-transform: capitalize;"
              class="q-mr-md" />
            <q-btn label="Delete" color="negative" style="text-transform: capitalize;"
              @click="openDeleteWorkerModal(worker)" />
          </q-card-actions>
        </q-card>
      </div>
    </div>

    <!-- Pagination Section -->
    <div class="row justify-center q-mt-md">
      <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
    </div>

    <!-- Modal Components -->
    <AddWorkerModal :fetchWorkers="fetchWorkers" />
    <EditWorkerModal :fetchWorkers="fetchWorkers" :editData="workerData" />
    <DeleteWorkerModal :fetchWorkers="fetchWorkers" :deleteData="workerData" />
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import workerService from 'src/services/workerService';

export default {
  components: {
    AddWorkerModal: defineAsyncComponent(() => import('components/Modals/Worker/AddWorker.vue')),
    EditWorkerModal: defineAsyncComponent(() => import('components/Modals/Worker/EditWorker.vue')),
    DeleteWorkerModal: defineAsyncComponent(() => import('components/Modals/Worker/DeleteWorker.vue')),
  },
  data() {
    return {
      workers: [],
      workerData: {},
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
        this.fetchWorkers();
      }, 1000);
    },
  },
  methods: {
    openAddWorkerModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddWorkerModal(true);
    },
    openEditWorkerModal(editData) {
      this.workerData = editData;
      const modalStore = useModalStore();
      modalStore.setShowEditWorkerModal(true);
    },
    openDeleteWorkerModal(deleteData) {
      this.workerData = deleteData;
      const modalStore = useModalStore();
      modalStore.setShowDeleteWorkerModal(true);
    },
    formatTime(time) {
      const [hours, minutes, seconds] = time.split(':');

      const date = new Date(1970, 0, 1, hours, minutes, seconds);

      return new Intl.DateTimeFormat('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
      }).format(date);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchWorkers();
    },
    filterWorkers() {
      this.fetchWorkers();
    },
    async fetchWorkers() {
      try {
        const response = await workerService.getPaginatedWorkers({
          page: this.currentPage,
          pageSize: this.pageSize,
          search: this.search,
        });
        this.workers = response.data.body;
        this.total = response.data.details.total;
        this.lastPage = Math.ceil(this.total / this.pageSize);
      } catch (error) {
        console.error('Error fetching workers:', error);
      }
    },
  },
  mounted() {
    this.fetchWorkers();
  },
};
</script>
