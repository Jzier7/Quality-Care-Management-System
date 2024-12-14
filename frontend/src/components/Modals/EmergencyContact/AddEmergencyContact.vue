<template>
  <q-dialog v-model="modalStore.showAddEmergencyContactModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-primary" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Emergency Contact</h3>
      <q-form @submit.prevent="submitEmergencyContact">
        <q-input v-model="form.name" label="Name" dense outlined class="q-mb-md" />

        <CustomCroppa @imageCropped="updateCroppedImage" />

        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="submitEmergencyContact"></q-btn>
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
import emergencyContactService from 'src/services/emergencyContactService';

export default {
  components: {
    CustomCroppa: defineAsyncComponent(() => import('components/Widgets/CustomCroppa.vue')),
  },
  props: {
    fetchEmergencyContacts: {
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
      this.modalStore.setShowAddEmergencyContactModal(false);
      this.resetForm();
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
    async submitEmergencyContact() {
      const formData = new FormData();
      formData.append('name', this.form.name);

      if (this.form.croppedImage) {
        formData.append('file', this.form.croppedImage);
      }

      try {
        const response = await emergencyContactService.storeEmergencyContact(formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message || 'Emergency contact added successfully!'
        });

        this.fetchEmergencyContacts();
        this.closeModal();
        this.resetForm();

      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Failed to add emergency contact.'
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
