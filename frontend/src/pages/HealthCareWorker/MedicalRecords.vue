<template>
  <q-page class="q-pa-md">
    <q-toolbar class="q-pa-none py-4">
      <q-toolbar-title>
        <h1 class="text-dark text-3xl font-bold">Medical Records</h1>
      </q-toolbar-title>
    </q-toolbar>

    <q-toolbar class="q-pa-none justify-between items-center">
      <q-input
        outlined
        dense
        color='primary'
        v-model="search"
        placeholder="Search"
        @input="filterRows"
      >
        <template v-slot:prepend>
          <q-icon name="search" />
        </template>
      </q-input>

      <q-btn
        label="Add Worker"
        color="primary"
        @click="showAddWorkerDialog = true"
      />
    </q-toolbar>

    <q-list bordered class="rounded-borders mt-4">
      <q-item-label header>Healthcare Workers</q-item-label>

      <q-item
        v-for="worker in filteredWorkers"
        :key="worker.id"
      >
        <q-item-section avatar top>
          <q-icon name="person" color="black" size="34px" />
        </q-item-section>

        <q-item-section top>
          <q-item-label lines="1">
            <span class="text-weight-medium text-lg">{{ worker.name }}</span>
          </q-item-label>
          <q-item-label caption>
            <div class="text-grey-7">LICENSE NUMBER: {{ worker.licenseNumber }}</div>
            <div class="text-grey-7">DEPARTMENT: {{ worker.department }}</div>
            <div class="text-grey-7">SHIFTING: {{ worker.shifting }}</div>
            <div class="text-grey-7">POSITION: {{ worker.role }}</div>
          </q-item-label>
        </q-item-section>

        <q-item-section top side>
          <div class="text-grey-8 q-gutter-xs">
            <q-btn size="12px" flat dense round icon="delete" @click="removeWorker(worker.id)" />
            <q-btn size="12px" flat dense round icon="edit" />
          </div>
        </q-item-section>
      </q-item>

    </q-list>

    <!-- Add Worker Dialog -->
    <q-dialog v-model="showAddWorkerDialog">
      <q-card>
        <q-card-section>
          <q-form @submit="addWorker">
            <q-input v-model="newWorker.name" label="Name" outlined dense />
            <q-input v-model="newWorker.licenseNumber" label="License Number" outlined dense />
            <q-input v-model="newWorker.department" label="Department" outlined dense />
            <q-input v-model="newWorker.shifting" label="Shifting" outlined dense />
            <q-input v-model="newWorker.role" label="Position" outlined dense />
            <q-btn label="Add" type="submit" color="primary" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      search: '',
      showAddWorkerDialog: false,
      newWorker: {
        name: '',
        licenseNumber: '',
        department: '',
        shifting: '',
        role: '',
      },
      workers: [
        { id: 1, name: 'Juan Dela Cruz', licenseNumber: '12-364-5862-6592-5992', department: 'OB GYNE', shifting: '8:00AM-5:00PM', role: 'Doctor' },
        { id: 2, name: 'Jane Smith', licenseNumber: '12-364-5862-6592-5993', department: 'OB GYNE', shifting: '8:00AM-5:00PM', role: 'Nurse' },
      ],
    };
  },
  computed: {
    filteredWorkers() {
      const searchLower = this.search.toLowerCase();
      return this.workers.filter(worker =>
        worker.name.toLowerCase().includes(searchLower) ||
        worker.role.toLowerCase().includes(searchLower) ||
        worker.department.toLowerCase().includes(searchLower)
      );
    },
  },
  methods: {
    addWorker() {
      const newId = this.workers.length ? this.workers[this.workers.length - 1].id + 1 : 1;
      this.workers.push({
        id: newId,
        ...this.newWorker,
      });
      this.newWorker = { name: '', licenseNumber: '', department: '', shifting: '', role: '' };
      this.showAddWorkerDialog = false;
    },
    removeWorker(id) {
      this.workers = this.workers.filter(worker => worker.id !== id);
    },
  },
};
</script>
