<template>
  <q-dialog v-model="modalStore.showDeleteEmergencyContactModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Emergency Contact</h3>
      <div class="text-primary q-mb-md">
        <p>
          Are you sure you want to delete the emergency contact
          "<strong>{{ localForm.name }}</strong>"?
        </p>
        <p class="text-warning">Note: Deleting this contact is irreversible.</p>
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
import emergencyContactService from 'src/services/emergencyContactService';

export default {
  props: {
    fetchEmergencyContacts: {
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
        id: this.deleteData?.id,
        name: this.deleteData?.name,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue?.id;
        this.localForm.name = newValue?.name;
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteEmergencyContactModal(false);
    },
    async confirmDelete() {
      try {
        const response = await emergencyContactService.deleteEmergencyContact({
          id: this.localForm.id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message || 'Emergency contact deleted successfully.',
        });

        this.fetchEmergencyContacts();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error deleting emergency contact.',
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
