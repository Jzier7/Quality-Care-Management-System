<template>
  <q-page padding>
    <!-- Toolbar Section -->
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">Patients</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <!-- Add Patient and Search Bar Section -->
    <div class="row justify-between items-center">
      <q-btn dense label="Add Patient" color="primary" @click="openAddPatientModal"
        style="text-transform: capitalize;" />
      <form @submit.prevent="filterPatients" class="q-gutter-md">
        <q-input rounded outlined dense v-model="search" placeholder="Search Patients" @input="filterPatients"
          color="primary">
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </form>
    </div>

    <!-- Display Patients Section -->
    <div class="q-mt-md">
      <div v-if="patientsWithAge.length === 0" class="row justify-center q-ma-lg">
        <p class="text-h6 text-gray-600">No patients found.</p>
      </div>

      <div class="q-gutter-sm">
        <q-card v-for="patient in patientsWithAge" :key="patient.id" flat bordered
          class="q-pa-sm border border-gray-300 rounded-lg shadow-sm hover:shadow-lg transition-shadow">
          <q-card-section class="overflow-hidden">
            <div class="row items-center q-gutter-sm">
              <!-- Profile Picture -->
              <div class="q-pa-md">
                <q-img v-if="patient.profile_picture" :src="patient.profile_picture" alt="Patient Profile"
                  class="rounded-full" style="width: 100px; height: 100px; object-fit: cover;" />
                <q-icon v-else name="account_circle" size="100px" color="primary" class="rounded-full" />
              </div>

              <!-- Patient Information -->
              <div>
                <h2 class="font-bold text-primary text-h6">
                  {{ patient.user.last_name }},
                  {{ patient.user.first_name }}
                  <span v-if="patient.user.middle_name" class="text-body1">{{ patient.user.middle_name }}</span>
                </h2>
                <p class="text-gray-500 text-body2"><strong>Age:</strong> {{ patient.age }}</p>
                <p class="text-gray-500 text-body2"><strong>Address:</strong> {{ patient.address }}</p>
                <p class="text-gray-500 text-body2"><strong>Emergency Contact:</strong> {{ patient.emergency_contact }}
                </p>
                <p class="text-gray-500 text-body2"><strong>Sex:</strong> {{ patient.sex }}</p>
                <p class="text-gray-500 text-body2"><strong>Status:</strong> {{ patient.status }}</p>
              </div>
            </div>
          </q-card-section>

          <!-- Actions -->
          <q-card-actions class="row justify-end">
            <q-btn label="Edit" color="primary" @click="openEditPatientModal(patient)"
              style="text-transform: capitalize;" class="q-mr-md" />
            <q-btn label="Delete" color="negative" style="text-transform: capitalize;"
              @click="openDeletePatientModal(patient)" />
          </q-card-actions>
        </q-card>
      </div>
    </div>

    <!-- Pagination Section -->
    <div class="row justify-center q-mt-md">
      <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
    </div>

    <!-- Modal Components -->
    <AddPatientModal :fetchPatients="fetchPatients" />
    <EditPatientModal :fetchPatients="fetchPatients" :editData="patientData" />
    <DeletePatientModal :fetchPatients="fetchPatients" :deleteData="patientData" />
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import patientService from 'src/services/patientService';

export default {
  components: {
    AddPatientModal: defineAsyncComponent(() => import('components/Modals/Patient/AddPatient.vue')),
    EditPatientModal: defineAsyncComponent(() => import('components/Modals/Patient/EditPatient.vue')),
    DeletePatientModal: defineAsyncComponent(() => import('components/Modals/Patient/DeletePatient.vue')),
  },
  data() {
    return {
      patients: [],
      patientData: {},
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
        this.fetchPatients();
      }, 1000);
    },
  },
  computed: {
    patientsWithAge() {
      return this.patients.map(patient => ({
        ...patient,
        age: this.calculateAge(patient.birthdate),
      }));
    }
  },
  methods: {
    openAddPatientModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddPatientModal(true);
    },
    openEditPatientModal(editData) {
      this.patientData = editData;
      const modalStore = useModalStore();
      modalStore.setShowEditPatientModal(true);
    },
    openDeletePatientModal(deleteData) {
      this.patientData = deleteData;
      const modalStore = useModalStore();
      modalStore.setShowDeletePatientModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchPatients();
    },
    filterPatients() {
      this.fetchPatients();
    },
    async fetchPatients() {
      try {
        const response = await patientService.getPaginatedPatients({
          page: this.currentPage,
          pageSize: this.pageSize,
          search: this.search,
        });
        this.patients = response.data.body;
        this.total = response.data.details.total;
        this.lastPage = Math.ceil(this.total / this.pageSize);
      } catch (error) {
        console.error('Error fetching patients:', error);
      }
    },
    calculateAge(birthdate) {
      const birthDate = new Date(birthdate);
      const ageDifMs = Date.now() - birthDate.getTime();
      const ageDate = new Date(ageDifMs);
      return Math.abs(ageDate.getUTCFullYear() - 1970);
    }
  },
  mounted() {
    this.fetchPatients();
  },
};
</script>
