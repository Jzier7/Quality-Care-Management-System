<template>
  <q-dialog v-model="modalStore.showAddPositionModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Position</h3>
      <q-form @submit.prevent>
        <q-input v-model="localForm.name" label="Position Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.description" label="Description" dense outlined class="q-mb-md" />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addPosition" />
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm" />
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import positionService from 'src/services/positionService';
import { useModalStore } from 'src/stores/modules/modalStore';

export default {
  props: {
    fetchPositions: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      localForm: {
        name: '',
        description: ''
      },
      errors: {},
      modalStore: useModalStore(),
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddPositionModal(false);
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        name: '',
        description: ''
      };
      this.errors = {};
    },
    async addPosition() {
      try {
        const response = await positionService.storePosition({ ...this.localForm });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: 'Position added successfully.',
        });

        this.fetchPositions();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding position.',
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
