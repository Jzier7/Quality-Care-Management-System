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
        <q-input v-model="localForm.patient.birthdate" label="Birthdate" dense outlined type="date" class="q-mb-md"
          :disable="!isEditing" :error-message="errors.birthdate ? errors.birthdate[0] : ''" />
        <q-input v-model="localForm.patient.address" label="Address" dense outlined class="q-mb-md"
          :disable="!isEditing" :error-message="errors.address ? errors.address[0] : ''" />
        <q-input v-model="localForm.patient.emergency_contact" label="Emergency Contact" dense outlined class="q-mb-md"
          :disable="!isEditing" :error-message="errors.emergency_contact ? errors.emergency_contact[0] : ''" />
        <q-select v-model="localForm.patient.sex" :options="sexOptions" label="Sex" dense outlined class="q-mb-md"
          :disable="!isEditing" :error-message="errors.sex ? errors.sex[0] : ''" />
      </div>
    </div>
  </q-page>
</template>

<script>
import { Notify } from 'quasar';
import { useUserStore } from 'src/stores/modules/userStore';

export default {
  name: 'UserInfo',
  data() {
    return {
      localForm: {
        first_name: '',
        middle_name: '',
        last_name: '',
        birthdate: '',
        address: '',
        emergency_contact: '',
        sex: '',
        patient: {
          birthdate: '',
          address: '',
          emergency_contact: '',
          sex: '',
        }
      },
      sexOptions: ['Male', 'Female'],
      isEditing: false,
      errors: {},
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
      const { success, response, error } = await userStore.updatePatient(this.localForm);
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

      const birthdate = userStore.userData.patient?.birthdate?.split(' ')[0] || '';

      this.localForm = {
        ...userStore.userData,
        patient: {
          id: userStore.userData.patient?.id || '',
          birthdate,
          address: userStore.userData.patient?.address || '',
          emergency_contact: userStore.userData.patient?.emergency_contact || '',
          sex: userStore.userData.patient?.sex || '',
        }
      };
    }
  },
  mounted() {
    this.fetchUserData();
  },
};
</script>

<style lang="scss" scoped>
.q-page {
  padding: 20px;
}
</style>
