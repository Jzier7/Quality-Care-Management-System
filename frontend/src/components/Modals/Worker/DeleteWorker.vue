<template>
  <q-dialog v-model="modalStore.showDeleteWorkerModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Worker</h3>
      <div class="text-primary q-mb-md">
        <p>Are you sure you want to delete the worker "<strong>{{ localForm.first_name }} {{ localForm.last_name
            }}</strong>"?</p>
        <p class="text-warning">Note: Deleting this worker is irreversible.</p>
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
import workerService from 'src/services/workerService';

export default {
  props: {
    fetchWorkers: {
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
        id: this.deleteData.user?.id,
        first_name: this.deleteData.user?.first_name,
        last_name: this.deleteData.user?.last_name,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue.user?.id;
        this.localForm.first_name = newValue.user?.first_name;
        this.localForm.last_name = newValue.user?.last_name;
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteWorkerModal(false);
    },
    async confirmDelete() {
      try {
        const response = await workerService.deleteWorker({
          id: this.localForm.id,
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
          message: error.response?.data?.message || 'Error deleting worker.',
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
