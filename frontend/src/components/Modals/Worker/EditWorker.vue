<template>
  <q-dialog v-model="modalStore.showEditWorkerModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Worker</h3>
      <q-form @submit.prevent>
        <q-input v-model="localForm.first_name" label="First Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.middle_name" label="Middle Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.last_name" label="Last Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.license_number" label="License Number" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.email" label="Email" dense outlined type="email" class="q-mb-md" />

        <q-select v-model="localForm.department" :options="departments" label="Department" dense outlined
          class="q-mb-md" />

        <q-select v-model="localForm.position" :options="positions" label="Position" option-value="id"
          option-label="name" dense outlined class="q-mb-md" />

        <q-input v-model="localForm.shift_start_time" label="Shift Start Time" dense outlined type="time"
          class="q-mb-md w-100" />

        <q-input v-model="localForm.shift_end_time" label="Shift End Time" dense outlined type="time"
          class="q-mb-md w-100" />

        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="editWorker" />
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
import positionService from 'src/services/positionService';

export default {
  props: {
    fetchWorkers: {
      type: Function,
      required: true,
    },
    editData: {
      type: Object,
      required: true,
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
      positions: [],
    };
  },
  watch: {
    editData: {
      immediate: true,
      handler(newValue) {
        this.setInitialData(newValue);
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditWorkerModal(false);
      this.clearForm();
    },
    clearForm() {
      this.errors = {};
    },
    setInitialData(editData) {
      if (editData) {
        this.localForm = {
          first_name: editData.user?.first_name || '',
          middle_name: editData.user?.middle_name || '',
          last_name: editData.user?.last_name || '',
          license_number: editData.license_number || '',
          email: editData.user?.email || '',
          department: editData.department || '',
          position: editData.position || '',
          shift_start_time: editData.shift_start_time || '',
          shift_end_time: editData.shift_end_time || '',
        };
      }
    },
    async editWorker() {
      try {
        const response = await workerService.updateWorker({
          id: this.editData.id,
          position_id: this.localForm.position.id,
          ...this.localForm,
        });

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
          message: error.response?.data?.message || 'Error editing worker.',
        });
      }
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
