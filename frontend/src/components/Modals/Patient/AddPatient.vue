<template>
  <q-dialog v-model="modalStore.showAddPatientModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Patient</h3>
      <q-form @submit.prevent>
        <q-input v-model="localForm.first_name" label="First Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.middle_name" label="Middle Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.last_name" label="Last Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.email" label="Email" dense outlined class="q-mb-md" type="email" />
        <q-input v-model="localForm.birthdate" label="Birthdate" dense outlined type="date" class="q-mb-md" />
        <q-input v-model="localForm.address" label="Address" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.emergency_contact" label="Emergency Contact" dense outlined class="q-mb-md" />
        <q-select v-model="localForm.sex" :options="sexOptions" label="Sex" dense outlined class="q-mb-md" />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addPatient" />
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm" />
        </div>
      </q-form>
    </q-card>
  </q-dialog>

  <!-- View Password Modal -->
  <q-dialog v-model="showPasswordModal">
    <q-card flat bordered class="q-pa-md text-black">
      <h3 class="text-primary pb-4">Patient's Password</h3>
      <div class="q-mb-md">
        <p>The newly created patient's password is:</p>
        <q-input v-model="patientPassword" readonly>
          <template v-slot:append>
            <q-btn round dense flat icon="content_copy" @click="copyPassword" />
          </template>
        </q-input>
        <p class="text-negative q-mt-md">Please take note of this password. It will only be displayed once!</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Close" color="primary" @click="closePasswordModal" />
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import patientService from 'src/services/patientService';
import { useModalStore } from 'src/stores/modules/modalStore';

export default {
  props: {
    fetchPatients: {
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
        email: '',
        birthdate: '',
        emergency_contact: '',
        sex: '',
      },
      errors: {},
      modalStore: useModalStore(),
      sexOptions: ['Male', 'Female'],
      patientPassword: '',
      showPasswordModal: false,
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddPatientModal(false);
    },
    closePasswordModal() {
      this.showPasswordModal = false;
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        birthdate: '',
        address: '',
        emergency_contact: '',
        sex: '',
      };
      this.errors = {};
      this.patientPassword = '';
    },
    async addPatient() {
      try {

        const formToSubmit = {
          ...this.localForm,
        };
        const response = await patientService.storePatient(formToSubmit);
        this.patientPassword = response.data.body;

        Notify.create({
          type: 'positive',
          position: 'top',
          message: 'Patient added successfully.',
        });

        this.fetchPatients();
        this.closeModal();
        this.showPasswordModal = true;
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding patient.',
        });

        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    copyPassword() {
      navigator.clipboard.writeText(this.patientPassword)
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
  },
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}
</style>
