<template>
  <q-dialog v-model="modalStore.showDeleteScheduleModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Schedule</h3>
      <div class="text-primary q-mb-md">
        <p>Are you sure you want to delete the schedule for "<strong>{{ localForm.title }}</strong>"?</p>
        <p class="text-warning">Note: Deleting this schedule is irreversible.</p>
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
import scheduleService from 'src/services/scheduleService';

export default {
  props: {
    fetchSchedules: {
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
        title: this.deleteData?.title,
        date: this.deleteData?.date,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue?.id;
        this.localForm.title = newValue?.title;
        this.localForm.date = newValue?.date;
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteScheduleModal(false);
    },
    async confirmDelete() {
      try {
        const response = await scheduleService.deleteSchedule({
          id: this.localForm.id,
        });

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
          message: error.response?.data?.message || 'Error deleting schedule.',
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
