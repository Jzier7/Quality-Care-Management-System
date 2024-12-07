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
          <div class="q-col-12 q-col-md-6">

            <q-card bordered class="q-pa-md">
              <h5 class="text-primary">Medical Records</h5>
              <q-input v-model="newRecord.serial_number" label="Serial Number" dense outlined class="q-mb-md" />
              <q-input v-model="newRecord.date" label="Date" dense outlined class="q-mb-md" type="date" />
              <q-input v-model="newRecord.diagnosis" label="Diagnosis" dense outlined class="q-mb-md" />
              <q-input v-model="newRecord.prescriptions" label="Prescriptions" dense outlined class="q-mb-md" />
              <q-btn flat color="secondary" icon="save" @click="saveRecord" label="Save Record" />
            </q-card>

            <div v-if="patientData.medicalRecords && patientData.medicalRecords.length">
              <q-card bordered class="q-pa-md">
                <ul>
                  <li v-for="(record, index) in patientData.medicalRecords" :key="index">
                    <p><strong>{{ record.title }}:</strong> {{ record.details }}</p>
                    <p><strong>Date:</strong> {{ record.date }}</p>
                    <q-btn flat color="negative" icon="delete" @click="deleteRecord(index)" />
                  </li>
                </ul>
              </q-card>
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
              <!-- Status Field -->
              <q-select v-model="patientData.status" :options="statusOptions" label="Status" dense outlined
                class="q-mb-md" @update:model-value="updateStatus" />
            </q-card>
          </div>
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import patientService from 'src/services/patientService';

export default {
  props: {
    fetchPatients: {
      type: Function,
      required: true
    },
    viewData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      modalStore: useModalStore(),
      sexOptions: [
        { label: 'Male', value: 'male' },
        { label: 'Female', value: 'female' }
      ],
      statusOptions: [
        { label: 'Admitted', value: 'Admitted' },
        { label: 'Discharged', value: 'Discharged' },
        { label: 'Under Treatment', value: 'Under Treatment' },
        { label: 'Transferred', value: 'Transferred' },
      ],
      patientData: {
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        birthdate: '',
        address: '',
        emergency_contact: '',
        sex: '',
        age: '',
        status: '',
        medicalRecords: [],
      },
      newRecord: {
        title: '',
        details: '',
        date: ''
      }
    };
  },
  watch: {
    viewData: {
      immediate: true,
      handler(newData) {
        this.patientData = { ...newData };
        this.patientData.age = this.calculateAge(this.patientData.birthdate);
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowViewPatientModal(false);
    },
    async updateStatus() {
      try {
        const response = await patientService.updatePatientStatus({
          id: this.patientData.id,
          status: this.patientData.status.value
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
    calculateAge(birthdate) {
      const birthDate = new Date(birthdate);
      const ageDifMs = Date.now() - birthDate.getTime();
      const ageDate = new Date(ageDifMs);
      return Math.abs(ageDate.getUTCFullYear() - 1970);
    },
    addRecord() {
      // Add the new record to the patient's medical records
      if (this.newRecord.title && this.newRecord.details && this.newRecord.date) {
        this.patientData.medicalRecords.push({ ...this.newRecord });
        this.newRecord = { title: '', details: '', date: '' }; // Reset the form
        Notify.create({
          type: 'positive',
          position: 'top',
          message: 'Medical record added successfully.',
        });
      } else {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: 'Please fill in all fields.',
        });
      }
    },
    deleteRecord(index) {
      // Remove the record from the medical records array
      this.patientData.medicalRecords.splice(index, 1);
      Notify.create({
        type: 'positive',
        position: 'top',
        message: 'Medical record deleted successfully.',
      });
    }
  },
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}

/* Position the close button at the top right corner */
.q-card-section {
  position: relative;
}

.q-btn.q-mb-none.q-mr-none {
  position: absolute;
  top: 0;
  right: 0;
}

/* Ensure columns are aligned side by side */
.q-row {
  display: flex;
  flex-wrap: nowrap;
  gap: 1rem;
}

.q-col-12 {
  flex: 1;
}

/* Media Query: Switch the column order on small screens (mobile) */
@media (max-width: 767px) {
  .flex-column-reverse-on-mobile {
    flex-direction: column-reverse;
  }
}
</style>
