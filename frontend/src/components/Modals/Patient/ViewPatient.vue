<template>
  <q-dialog v-model="modalStore.showViewPatientModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 80vw; max-width: 1200px;">
      <!-- Header with Close Button -->
      <div class="q-card-section row items-center justify-between">
        <h3 class="text-primary pb-4">View Patient</h3>
        <q-btn flat round icon="close" color="black" @click="closeModal" class="q-mb-none q-mr-none" />
      </div>

      <q-form @submit.prevent>
        <div class="q-row no-wrap flex-column-reverse-on-mobile">
          <div class="q-col-12 q-col-md-6 scrollable-column">

            <h5 class="text-primary">Medical Records</h5>

            <div class="row justify-between items-center">
              <q-btn v-if="isWorker" label="Add Record" color="primary" @click="openAddRecordModal"
                style="text-transform: capitalize;" />

              <form @submit.prevent="filterRecords" class="q-gutter-md">
                <q-input rounded outlined dense v-model="search" placeholder="Search Records" @input="filterRecords"
                  color="primary">
                  <template v-slot:prepend>
                    <q-icon name="search" />
                  </template>
                </q-input>
              </form>
            </div>

            <!-- List of Existing Records -->
            <div v-if="records && records.length">
              <div v-for="record in records" :key="record.id" class="scrollable-records">
                <q-card bordered class="q-pa-md q-my-md">
                  <ul class="text-dark">
                    <li>
                      <p><strong>Serial Number:</strong> {{ record.serial_number }}</p>
                      <p><strong>Date:</strong> {{ formatDate(record.date, ' MMM D YYYY') }}</p>
                      <p><strong>Diagnosis:</strong> {{ record.diagnosis }}</p>
                      <p><strong>Prescriptions:</strong></p>
                      <div v-html="record.prescriptions"></div>
                      <div v-if="isWorker" class="row justify-end">
                        <q-btn flat color="negative" icon="delete" @click="openDeleteRecordModal(record)"
                          label="Delete" />
                      </div>
                    </li>
                  </ul>
                </q-card>
              </div>

              <div class="row justify-center q-mt-md">
                <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
              </div>
            </div>
          </div>

          <div class="q-col-12 q-col-md-6">
            <q-card bordered class="q-pa-md">
              <h5 class="text-primary">Patient Information</h5>
              <q-input v-model="patientData.user.first_name" label="First Name" dense readonly outlined
                class="q-mb-md" />
              <q-input v-model="patientData.user.middle_name" label="Middle Name" dense readonly outlined
                class="q-mb-md" />
              <q-input v-model="patientData.user.last_name" label="Last Name" dense readonly outlined class="q-mb-md" />
              <q-input v-model="patientData.age" label="Age" dense readonly outlined class="q-mb-md" />
              <q-input v-model="patientData.address" label="Address" dense readonly outlined class="q-mb-md" />
              <q-input v-model="patientData.emergency_contact" label="Emergency Contact" dense readonly outlined
                class="q-mb-md" />
              <q-select v-model="patientData.sex" :options="sexOptions" label="Sex" dense readonly outlined
                class="q-mb-md" />
              <q-select v-model="patientData.status" :options="statusOptions" label="Status" dense outlined
                class="q-mb-md" @update:model-value="updateStatus" />
            </q-card>
          </div>
        </div>
      </q-form>
    </q-card>

    <AddRecordModal :fetchRecords="fetchRecords" :patientId="patientData.id" />
    <DeleteRecordModal :fetchRecords="fetchRecords" :deleteData="recordData" />
  </q-dialog>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import patientService from 'src/services/patientService';
import medicalRecordService from 'src/services/medicalRecordService';
import handleDateTime from 'src/utils/mixin/handleDateTime';
import { USER_ROLES } from 'src/utils/constants';
import { useUserStore } from 'src/stores/modules/userStore';

export default {
  components: {
    AddRecordModal: defineAsyncComponent(() => import('components/Modals/Patient/AddRecord.vue')),
    DeleteRecordModal: defineAsyncComponent(() => import('components/Modals/Patient/DeleteRecord.vue')),
  },
  props: {
    fetchPatients: Function,
    viewData: Object,
  },
  mixins: [handleDateTime],
  data() {
    return {
      modalStore: useModalStore(),
      sexOptions: [
        { label: 'Male', value: 'male' },
        { label: 'Female', value: 'female' },
      ],
      statusOptions: [
        { label: 'Admitted', value: 'Admitted' },
        { label: 'Discharged', value: 'Discharged' },
        { label: 'Under Treatment', value: 'Under Treatment' },
        { label: 'Transferred', value: 'Transferred' },
      ],
      patientData: {},
      newRecord: {
        serial_number: '',
        date: new Date().toISOString().split('T')[0],
        diagnosis: '',
        prescriptions: '',
      },
      records: [],
      recordData: {},
      search: '',
      currentPage: 1,
      pageSize: 5,
      lastPage: 1,
      total: 0,
    };
  },
  watch: {
    viewData: {
      immediate: true,
      handler(newData) {
        this.patientData = { ...newData };
        this.patientData.age = this.calculateAge(this.patientData.birthdate);

        if (this.patientData.id) {
          this.fetchRecords();
        }
      },
    },
    search() {
      this.currentPage = 1;

      clearTimeout(this.debounceTimeout);

      this.debounceTimeout = setTimeout(() => {
        this.fetchRecords();
      }, 1000);
    },
  },
  computed: {
    isWorker() {
      const userStore = useUserStore();
      return userStore.userData?.role.slug === USER_ROLES.WORKER;
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowViewPatientModal(false);
    },
    openAddRecordModal() {
      this.modalStore.setShowAddRecordModal(true);
    },
    openDeleteRecordModal(deleteData) {
      this.recordData = deleteData;
      this.modalStore.setShowDeleteRecordModal(true);
    },
    calculateAge(birthdate) {
      const birthDate = new Date(birthdate);
      const ageDifMs = Date.now() - birthDate.getTime();
      const ageDate = new Date(ageDifMs);
      return Math.abs(ageDate.getUTCFullYear() - 1970);
    },
    async fetchRecords() {
      try {
        const response = await medicalRecordService.getPaginatedMedicalRecords({
          page: this.currentPage,
          pageSize: this.pageSize,
          search: this.search,
          patient: this.patientData.id
        });
        this.records = response.data.body;
        this.total = response.data.details.total;
        this.lastPage = Math.ceil(this.total / this.pageSize);
      } catch (error) {
        console.error('Error fetching patients:', error);
      }
    },
    async saveRecord() {
      try {
        await medicalRecordService.storeMedicalRecord({
          patient_id: this.patientData.id,
          ...this.newRecord
        });

        await this.fetchRecords();
        this.newRecord = {
          serial_number: '',
          date: new Date().toISOString().split('T')[0],
          diagnosis: '',
          prescriptions: '',
        };
        Notify.create({
          type: 'positive',
          position: 'top',
          message: 'Medical record added successfully.',
        });
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: 'Error updating patient status.',
        });
      }
    },
    async deleteRecord(id) {
      await medicalRecordService.deleteMedicalRecord({ id: id });

      await this.fetchRecords();
      Notify.create({
        type: 'positive',
        position: 'top',
        message: 'Medical record deleted successfully.',
      });
    },
    async updateStatus() {
      try {
        await patientService.updatePatientStatus({
          id: this.patientData.id,
          status: this.patientData.status,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: 'Patient status updated successfully.',
        });
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: 'Error updating patient status.',
        });
      }
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchRecords();
    },
    filterRecords() {
      this.fetchRecords();
    },
  },
};
</script>

<style lang="scss" scoped>
.text-bold {
  font-weight: bold;
}

.q-card-section {
  position: relative;
}

.q-btn.q-mb-none.q-mr-none {
  position: absolute;
  top: 0;
  right: 0;
}

.q-row {
  display: flex;
  flex-wrap: nowrap;
  gap: 1rem;
}

.q-col-12 {
  flex: 1;
}

@media (max-width: 767px) {
  .flex-column-reverse-on-mobile {
    flex-direction: column-reverse;
  }
}

.editor {
  color: $primary;
}

.scrollable-records {
  max-height: 400px;
  overflow-y: auto;
}

.scrollable-column {
  max-height: 60vh;
  overflow-y: auto;
  padding-right: 1rem;
}
</style>
