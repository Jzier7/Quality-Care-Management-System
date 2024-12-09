<template>
  <q-dialog v-model="modalStore.showAddScheduleModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Schedule</h3>
      <q-form @submit.prevent>
        <q-input v-model="localForm.title" label="Title" dense outlined class="q-mb-md" />
        <q-select v-model="localForm.patient" :options="patients" option-value="id" option-label="name" label="Patient"
          dense outlined class="q-mb-md" />

        <div class="q-mb-md">
          <div class="q-pb-sm">
            <q-item-label class="text-dark">Date</q-item-label>
          </div>
          <div class="row text-dark">
            <div class="col-5">
              <q-date v-model="localForm.start_date" label="Date" dense outlined />
            </div>
          </div>
        </div>

        <div class="q-mb-md">
          <div class="row justify-between items-center text-dark q-gutter-md">
            <div class="col-12 col-md-5">
              <q-item-label class="text-dark">Start Time</q-item-label>
              <q-input v-model="localForm.start_time" label="Start Time" dense outlined type="time" class="q-mb-md" />
            </div>
            <div class="col-12 col-md-5">
              <q-item-label class="text-dark">End Time</q-item-label>
              <q-input v-model="localForm.end_time" label="End Time" dense outlined type="time" class="q-mb-md" />
            </div>
          </div>
        </div>

        <div class="row justify-end">
          <q-btn label="Add Schedule" color="primary" @click="addSchedule" />
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm" />
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import scheduleService from 'src/services/scheduleService';
import patientService from 'src/services/patientService';
import { useModalStore } from 'src/stores/modules/modalStore';

export default {
  props: {
    fetchSchedules: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      localForm: {
        title: '',
        patient: '',
        start_date: '',
        start_time: '',
        end_time: '',
      },
      errors: {},
      modalStore: useModalStore(),
      patients: []
    };
  },
  mounted() {
    this.fetchPatients();
    this.setDefaultDate();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddScheduleModal(false);
    },
    clearForm() {
      this.localForm = {
        title: '',
        patient: '',
        start_date: '',
        start_time: '',
        end_time: '',
      };
      this.errors = {};
    },
    setDefaultDate() {
      const today = new Date();
      const formattedDate = today.toISOString().split('T')[0];
      this.localForm.start_date = formattedDate;
      this.localForm.end_date = formattedDate;
    },
    async addSchedule() {
      try {
        const startDateTime = `${this.localForm.start_date} ${this.localForm.start_time}`;
        const endDateTime = `${this.localForm.start_date} ${this.localForm.end_time}`;

        const scheduleData = {
          title: this.localForm.title,
          patient_id: this.localForm.patient.id,
          start: startDateTime,
          end: endDateTime,
        };

        const response = await scheduleService.storeSchedule(scheduleData);

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.fetchSchedules();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding schedule.',
        });
      }
    },
    async fetchPatients() {
      try {
        const response = await patientService.getAllPatients();
        this.patients = response.data.body;
      } catch (error) {
        console.error('Error fetching schedules:', error);
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
