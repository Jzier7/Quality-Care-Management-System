<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">MY ACCOUNT</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none">
      <div class="q-mb-md q-gutter-sm flex items-center">
        <q-btn color="primary" label="Update Info" @click="enableEditing" v-if="!isEditing" />
        <q-btn color="secondary" label="Save" @click="updateUserInfo" v-if="isEditing" />
        <q-btn color="grey" label="Cancel" @click="cancelEditing" v-if="isEditing" />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
        <q-input v-model="localForm.first_name" label="First Name" dense outlined class="q-mb-md" :disable="!isEditing"
          :error-message="errors.first_name ? errors.first_name[0] : ''" />
        <q-input v-model="localForm.middle_name" label="Middle Name" dense outlined class="q-mb-md"
          :disable="!isEditing" :error-message="errors.middle_name ? errors.middle_name[0] : ''" />
        <q-input v-model="localForm.last_name" label="Last Name" dense outlined class="q-mb-md" :disable="!isEditing"
          :error-message="errors.last_name ? errors.last_name[0] : ''" />
        <q-input v-model="localForm.health_care_worker.license_number" label="License Number" dense outlined
          class="q-mb-md" :disable="!isEditing"
          :error-message="errors.license_number ? errors.license_number[0] : ''" />

        <q-select v-model="localForm.health_care_worker.department" :options="departments" label="Department" dense
          outlined class="q-mb-md" :disable="!isEditing"
          :error-message="errors.department ? errors.department[0] : ''" />

        <q-select v-model="localForm.health_care_worker.position" :options="positions" label="Position"
          option-value="id" option-label="name" dense outlined class="q-mb-md" :disable="!isEditing"
          :error-message="errors.position ? errors.position[0] : ''" />

        <q-input v-model="localForm.health_care_worker.shift_start_time" label="Shift Start Time" dense outlined
          type="time" class="q-mb-md w-100" :disable="!isEditing"
          :error-message="errors.shift_start_time ? errors.shift_start_time[0] : ''" />

        <q-input v-model="localForm.health_care_worker.shift_end_time" label="Shift End Time" dense outlined type="time"
          class="q-mb-md w-100" :disable="!isEditing"
          :error-message="errors.shift_end_time ? errors.shift_end_time[0] : ''" />
      </div>
    </div>
  </q-page>
</template>

<script>
import { Notify } from 'quasar';
import { useUserStore } from 'src/stores/modules/userStore';
import positionService from 'src/services/positionService';

export default {
  name: 'UserInfo',
  data() {
    return {
      localForm: {
        first_name: '',
        middle_name: '',
        last_name: '',
        license_number: '',
        department: '',
        position: '',
        shift_start_time: '',
        shift_end_time: '',
        health_care_worker: {
          license_number: '',
          department: '',
          position: '',
          shift_start_time: '',
          shift_end_time: ''
        },
      },
      isEditing: false,
      errors: {},
      departments: [
        'Emergency', 'Cardiology', 'Orthopedics', 'Pediatrics', 'Radiology', 'OB-GYN', 'General Surgery',
        'Internal Medicine', 'Neurology', 'Dermatology', 'Anesthesiology', 'Psychiatry', 'Oncology', 'Pulmonology', 'Endocrinology',
      ],
      positions: [],
    };
  },
  methods: {
    enableEditing() {
      this.isEditing = true;
      this.errors = {};
    },
    cancelEditing() {
      this.isEditing = false;
      this.errors = {};
      this.fetchUserData();
    },
    async updateUserInfo() {
      const userStore = useUserStore();
      const formData = {
        ...this.localForm,
        health_care_worker: {
          ...this.localForm.health_care_worker,
          position_id: this.localForm.health_care_worker.position.id
        }
      };
      const { success, response, error } = await userStore.updateWorker(formData);
      if (success) {
        this.isEditing = false;
        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });
      } else {
        this.errors = error;
      }
    },
    async fetchUserData() {
      const userStore = useUserStore();
      await userStore.fetchUser();

      this.localForm = {
        ...userStore.userData,
        health_care_worker: userStore.userData.health_care_worker || {
          license_number: '',
          department: '',
          position: '',
          shift_start_time: '',
          shift_end_time: ''
        }
      };
    },
    async fetchPositions() {
      try {
        const response = await positionService.getAllPositions();
        this.positions = response.data.body;
      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    },
  },
  mounted() {
    this.fetchPositions();
    this.fetchUserData();
  },
};
</script>

<style lang="scss" scoped>
.q-page {
  padding: 20px;
}
</style>
