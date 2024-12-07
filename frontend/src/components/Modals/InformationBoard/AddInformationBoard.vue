<template>
  <q-dialog v-model="modalStore.showAddInformationBoardModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-primary" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Information Board</h3>
      <q-form @submit.prevent="submitInformationBoard">
        <q-input v-model="form.name" label="Name" dense outlined class="q-mb-md" />

        <CustomCroppa @imageCropped="updateCroppedImage" />

        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="submitInformationBoard"></q-btn>
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm"></q-btn>
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import informationBoardService from 'src/services/informationBoardService';

export default {
  components: {
    CustomCroppa: defineAsyncComponent(() => import('components/Widgets/CustomCroppa.vue')),
  },
  props: {
    fetchInformationBoards: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      form: {
        name: '',
        croppedImage: null,
      },
      modalStore: useModalStore(),
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddInformationBoardModal(false);
    },
    resetForm() {
      this.form = {
        name: '',
        croppedImage: null,
      };
    },
    updateCroppedImage(croppedImage) {
      this.form.croppedImage = croppedImage;
    },
    async submitInformationBoard() {
      const formData = new FormData();
      formData.append('name', this.form.name);

      if (this.form.croppedImage) {
        formData.append('file', this.form.croppedImage);
      }

      try {
        const response = await informationBoardService.storeInformationBoard(formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message || 'Information board added successfully!'
        });

        this.fetchInformationBoards();
        this.closeModal();
        this.resetForm();

      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Failed to add information board.'
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.editor {
  color: $primary;
}
</style>
