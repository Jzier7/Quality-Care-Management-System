<template>
  <q-dialog v-model="modalStore.showDeletePositionModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Position</h3>
      <div class="text-primary q-mb-md">
        <p>Are you sure you want to delete the position "<strong>{{ localForm.name }}</strong>"?</p>
        <p class="text-warning">Note: Deleting this position is irreversible.</p>
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
import positionService from 'src/services/positionService';

export default {
  props: {
    fetchPositions: {
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
        if (newValue) {
          this.localForm.id = newValue.id;
          this.localForm.name = newValue.name;
        }
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeletePositionModal(false);
    },
    async confirmDelete() {
      try {
        const response = await positionService.deletePosition({
          id: this.localForm.id,
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
          message: error.response?.data?.message || 'Error deleting position.',
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
