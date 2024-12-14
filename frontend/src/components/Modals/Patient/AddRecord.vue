<template>
  <q-dialog v-model="modalStore.showAddRecordModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <!-- Header with Close Button -->
      <div class="q-card-section row items-center justify-between">
        <h3 class="text-primary pb-4">Add Medical Record</h3>
        <q-btn flat round icon="close" color="black" @click="closeModal" class="q-mb-none q-mr-none" />
      </div>

      <q-form @submit.prevent>
        <!-- New Record Form -->
        <q-card bordered class="q-pa-md">
          <h5 class="text-primary">Record Information</h5>
          <q-input v-model="newRecord.serial_number" label="Serial Number" dense outlined class="q-mb-md" />
          <q-input v-model="newRecord.date" label="Date" dense outlined class="q-mb-md" type="date" />
          <q-select v-model="newRecord.worker" :options="workers" option-value="id" option-label="name"
            label="Healthcare Worker" dense outlined class="q-mb-md" />
          <q-input v-model="newRecord.diagnosis" label="Diagnosis" dense outlined class="q-mb-md" />
          <q-editor v-model="newRecord.prescriptions" label="Prescriptions" dense outlined class="q-mb-md editor"
            min-height="10rem" />
          <q-btn flat color="secondary" icon="save" @click="saveRecord" label="Save Record" />
        </q-card>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import medicalRecordService from 'src/services/medicalRecordService';
import workerService from 'src/services/workerService';

export default {
  props: {
    patientId: String,
    fetchRecords: Function,
  },
  data() {
    return {
      modalStore: useModalStore(),
      newRecord: {
        serial_number: '',
        worker: '',
        date: new Date().toISOString().split('T')[0],
        diagnosis: '',
        prescriptions: '',
      },
      workers: []
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddRecordModal(false);
      this.resetForm();
    },
    resetForm() {
      this.newRecord = {
        serial_number: '',
        date: new Date().toISOString().split('T')[0],
        diagnosis: '',
        prescriptions: '',
        worker: null,
      };
    },
    async saveRecord() {
      try {
        await medicalRecordService.storeMedicalRecord({
          patient_id: this.patientId,
          healthcare_worker_id: this.newRecord.worker.id,
          ...this.newRecord,
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
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message,
        });
      }
    },
    async fetchWorkers() {
      try {
        const response = await workerService.getAllWorkers();
        this.workers = response.data.body;
      } catch (error) {
        console.error('Error fetching workers:', error);
      }
    },
  },
  mounted() {
    this.fetchWorkers();
  }
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

.editor {
  color: $primary;
}
</style>
