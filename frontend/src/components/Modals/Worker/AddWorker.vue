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

        <q-select v-model="localForm.position" :options="positions" label="Position" option-value="id"
          option-label="name" dense outlined class="q-mb-md" />

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

  <!-- View Password Modal -->
  <q-dialog v-model="showPasswordModal">
    <q-card flat bordered class="q-pa-md text-black">
      <h3 class="text-primary pb-4">Worker's Password</h3>
      <div class="q-mb-md">
        <p>Your created worker's password is:</p>
        <q-input v-model="workerPassword" readonly>
          <template v-slot:append>
            <q-btn round dense flat icon="content_copy" @click="copyPassword" />
          </template>
        </q-input>
        <p class="text-negative q-mt-md">Please take a note of this password. It will only be displayed once!</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Close" color="primary" @click="closePasswordModal" />
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import workerService from 'src/services/workerService';
import positionService from 'src/services/positionService';
import { useModalStore } from 'src/stores/modules/modalStore';

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
        'Emergency', 'Cardiology', 'Orthopedics', 'Pediatrics', 'Radiology', 'OB-GYN', 'General Surgery',
        'Internal Medicine', 'Neurology', 'Dermatology', 'Anesthesiology', 'Psychiatry', 'Oncology', 'Pulmonology', 'Endocrinology',
      ],
      positions: [],
      workerPassword: '',
      showPasswordModal: false,
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddWorkerModal(false);
      this.clearForm();
    },
    closePasswordModal() {
      this.workerPassword = '';
      this.showPasswordModal = false;
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        first_name: '',
        middle_name: '',
        last_name: '',
        license_number: '',
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
        const response = await workerService.storeWorker({
          position_id: this.localForm.position.id,
          ...this.localForm
        });
        this.workerPassword = response.data.body;

        Notify.create({
          type: 'positive',
          position: 'top',
          message: 'Worker added successfully.',
        });


        this.fetchWorkers();
        this.showPasswordModal = true;
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
    copyPassword() {
      navigator.clipboard.writeText(this.workerPassword)
        .then(() => {
          Notify.create({
            type: 'positive',
            position: 'top',
            message: 'Password copied to clipboard!',
          });
        })
        .catch(() => {
          Notify.create({
            type: 'negative',
            position: 'top',
            message: 'Failed to copy password.',
          });
        });
    },
    async fetchPositions() {
      try {
        const response = await positionService.getAllPositions();
        this.positions = response.data.body;
      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    },
  },
  mounted() {
    this.fetchPositions();
  }
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}
</style>
