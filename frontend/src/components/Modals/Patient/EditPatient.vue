<template>
  <q-dialog v-model="modalStore.showEditPatientModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Patient</h3>
      <q-form @submit.prevent>
        <q-input v-model="localForm.first_name" label="First Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.middle_name" label="Middle Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.last_name" label="Last Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.birthdate" label="Birthdate" dense outlined type="date" class="q-mb-md" />
        <q-input v-model="localForm.address" label="Address" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.emergency_contact" label="Emergency Contact" dense outlined class="q-mb-md" />

        <q-select v-model="localForm.sex" :options="sexOptions" label="Sex" dense outlined class="q-mb-md" emit-value
          map-options />

        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="editPatient" />
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm" />
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
        birthdate: '',
        address: '',
        emergency_contact: '',
        sex: '',
        status: '',
      },
      modalStore: useModalStore(),
      sexOptions: [
        { label: 'Male', value: 'male' },
        { label: 'Female', value: 'female' }
      ],
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
      this.modalStore.setShowEditPatientModal(false);
      this.clearForm();
    },
    clearForm() {
      this.errors = {};
    },
    setInitialData(editData) {
      if (editData) {
        const birthdate = editData.birthdate ? editData.birthdate.split(' ')[0] : ''; // Extract only the date part
        this.localForm = {
          first_name: editData.user?.first_name || '',
          middle_name: editData.user?.middle_name || '',
          last_name: editData.user?.last_name || '',
          birthdate: birthdate || '',
          address: editData.address || '',
          emergency_contact: editData.emergency_contact || '',
          sex: editData.sex || '',
          status: editData.status || '',
        };
      }
    },
    async editPatient() {
      try {
        const response = await patientService.updatePatient({
          id: this.editData.id,
          ...this.localForm,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.fetchPatients();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error editing patient.',
        });
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
