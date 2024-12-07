<template>
  <q-dialog v-model="modalStore.showDeleteRecordModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Medical Record</h3>
      <div class="text-primary q-mb-md">
        <p>Are you sure you want to delete the medical record with serial number "<strong>{{ localForm.serial_number
            }}</strong>"?</p>
        <p class="text-warning">Note: Deleting this record is irreversible.</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Delete" color="negative" class="q-mr-sm" @click="confirmDelete"></q-btn>
        <q-btn label="Cancel" color="grey" @click="closeModal"></q-btn>
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import medicalRecordService from 'src/services/medicalRecordService';

export default {
  props: {
    fetchRecords: {
      type: Function,
      required: true,
    },
    deleteData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      localForm: {
        id: this.deleteData.id,
        serial_number: this.deleteData.serial_number,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue.id;
        this.localForm.serial_number = newValue.serial_number;
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteRecordModal(false);
    },
    async confirmDelete() {
      try {
        const response = await medicalRecordService.deleteMedicalRecord({
          id: this.localForm.id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.fetchRecords();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error deleting medical record.',
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
