<template>
  <q-dialog v-model="modalStore.showEditPositionModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Position</h3>
      <q-form @submit.prevent>
        <q-input v-model="localForm.name" label="Name" dense outlined class="q-mb-md" />
        <q-input v-model="localForm.description" label="Description" dense outlined class="q-mb-md" />

        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="editPosition" />
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm" />
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import positionService from 'src/services/positionService';

export default {
  props: {
    fetchPositions: {
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
        name: '',
        description: '',
      },
      modalStore: useModalStore(),
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
      this.modalStore.setShowEditPositionModal(false);
      this.clearForm();
    },
    clearForm() {
      this.errors = {};
    },
    setInitialData(editData) {
      if (editData) {
        this.localForm = {
          name: editData.name || '',
          description: editData.description || '',
        };
      }
    },
    async editPosition() {
      try {
        const response = await positionService.updatePosition({
          id: this.editData.id,
          ...this.localForm,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.fetchPositions();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error editing position.',
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
