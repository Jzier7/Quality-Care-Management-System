<template>
  <q-dialog v-model="modalStore.showAddWorkerModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Worker</h3>
      <q-form @submit.prevent>
        <q-input v-model="localForm.first_name" label="First Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.middle_name" label="Middle Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.last_name" label="Last Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.license_number" label="License Number" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.email" label="Email" dense outlined class="q-mb-md" type="email" />

        <q-select v-model="localForm.department" :options="departments" label="Department" dense outlined
          class="q-mb-md" />

        <q-select v-model="localForm.position" :options="positions" label="Position" dense outlined class="q-mb-md" />

        <q-input v-model="localForm.shift_start_time" label="Shift Start Time" dense outlined type="time"
          class="q-mb-md w-100" />

        <q-input v-model="localForm.shift_end_time" label="Shift End Time" dense outlined type="time"
          class="q-mb-md w-100" />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addWorker" />
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm" />
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import workerService from 'src/services/workerService';

export default {
  props: {
    fetchWorkers: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      localForm: {
        first_name: '',
        middle_name: '',
        last_name: '',
        license_number: '',
        email: '',
        department: '',
        position: '',
        shift_start_time: '',
        shift_end_time: '',
      },
      errors: {},
      modalStore: useModalStore(),
      departments: [
        'Emergency',
        'Cardiology',
        'Orthopedics',
        'Pediatrics',
        'Radiology',
        'OB-GYN',
        'General Surgery',
        'Internal Medicine',
        'Neurology',
        'Dermatology',
        'Anesthesiology',
        'Psychiatry',
        'Oncology',
        'Pulmonology',
        'Endocrinology',
      ],
      positions: [
        'Doctor',
        'Nurse',
        'Technician',
        'Therapist',
        'Medical Assistant',
        'Administrative Staff',
        'Maintenance Staff',
      ],
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddWorkerModal(false);
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        first_name: '',
        middle_name: '',
        last_name: '',
        licenseNumber: '',
        email: '',
        department: '',
        position: '',
        shift_start_time: '',
        shift_end_time: '',
      };
      this.errors = {};
    },
    async addWorker() {
      try {
        const response = await workerService.storeWorker({ ...this.localForm });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.fetchWorkers();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding worker.',
        });

        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
  },
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}
</style>
