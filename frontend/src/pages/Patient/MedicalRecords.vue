<template>
  <q-page class="q-pa-md">
    <!-- Header -->
    <div class="q-card-section row items-center justify-between">
      <h3 class="text-primary pb-4">Medical Records</h3>
    </div>

    <q-form @submit.prevent>
      <div class="q-row no-wrap flex-column-reverse-on-mobile">
        <div class="q-col-12 q-col-md-6 scrollable-column">

          <div v-if="records && records.length">
            <q-card flat bordered class="w-full shadow-lg">
              <q-card-section class="text-center">
                <div>
                  <h2 class="text-dark text-3xl font-bold">{{ records[0].healthcare_worker?.user?.first_name }} {{
                    records[0].healthcare_worker?.user?.last_name }}</h2>
                  <p class="text-dark text-xl">{{ records[0].healthcare_worker.position }}</p>
                  <p class="text-dark text-base">{{ records[0].serial_number }}</p>
                </div>

                <div class="form-fields mt-8 space-y-6">
                  <div class="form-row flex justify-between text-lg">
                    <span class="font-semibold text-dark">Patient's Name:</span>
                    <span class="text-dark">{{ records[0].patient?.user?.first_name }} {{
                      records[0].patient?.user?.last_name
                    }}</span>
                  </div>
                  <div class="form-row flex justify-between text-lg">
                    <span class="font-semibold text-dark">Date of Birth:</span>
                    <span class="text-dark">{{ formatDate(records[0].patient.birthdate, 'MMM D YYYY') }}</span>
                  </div>
                  <div class="form-row flex justify-between text-lg">
                    <span class="font-semibold text-dark">Gender:</span>
                    <span class="text-dark">{{ records[0].patient.sex }}</span>
                  </div>
                  <div class="form-row flex justify-between text-lg">
                    <span class="font-semibold text-dark">Date:</span>
                    <span class="text-dark">{{ formatDate(records[0].date, 'MMM D YYYY') }}</span>
                  </div>
                  <div class="form-row flex justify-between text-lg">
                    <span class="font-semibold text-dark">Diagnosis:</span>
                    <span class="text-dark">{{ records[0].diagnosis }}</span>
                  </div>
                  <div class="form-row flex justify-between text-lg">
                    <span class="font-semibold text-dark">Rx:</span>
                    <span class="text-dark" v-html="records[0].prescriptions"></span>
                  </div>
                </div>

                <q-card-section class="text-dark text-lg italic mt-10">
                  Doctor's Signature
                </q-card-section>
              </q-card-section>
            </q-card>
          </div>
        </div>

        <div class="q-col-12 q-col-md-6">
          <q-card flat bordered class="q-pa-md">
            <h5 class="text-primary">Patient Information</h5>
            <q-input v-if="records[0]?.patient?.user?.first_name" v-model="records[0].patient.user.first_name"
              label="First Name" dense readonly outlined class="q-mb-md" />
            <q-input v-if="records[0]?.patient?.user?.middle_name" v-model="records[0].patient.user.middle_name"
              label="Middle Name" dense readonly outlined class="q-mb-md" />
            <q-input v-if="records[0]?.patient?.user?.last_name" v-model="records[0].patient.user.last_name"
              label="Last Name" dense readonly outlined class="q-mb-md" />
            <q-input v-if="records[0]?.patient?.birthdate" v-model="userAge" label="Age" dense readonly outlined
              class="q-mb-md" />
            <q-input v-if="records[0]?.patient?.address" v-model="records[0].patient.address" label="Address" dense
              readonly outlined class="q-mb-md" />
            <q-input v-if="records[0]?.patient?.emergency_contact" v-model="records[0].patient.emergency_contact"
              label="Emergency Contact" dense readonly outlined class="q-mb-md" />
            <q-input v-if="records[0]?.patient?.sex" v-model="records[0].patient.sex" label="Sex" dense readonly
              outlined class="q-mb-md" />
            <q-input v-if="records[0]?.patient?.status" v-model="records[0].patient.status" label="Status" dense
              readonly outlined class="q-mb-md" />
          </q-card>
        </div>
      </div>
    </q-form>
  </q-page>
</template>

<script>
import medicalRecordService from 'src/services/medicalRecordService';
import handleDateTime from 'src/utils/mixin/handleDateTime';

export default {
  mixins: [handleDateTime],
  data() {
    return {
      records: [],
    };
  },
  computed: {
    userAge() {
      return this.calculateAge(this.records[0].patient?.birthdate);
    },
  },
  mounted() {
    this.fetchRecords()
  },
  methods: {
    calculateAge(birthdate) {
      const birthDate = new Date(birthdate);
      const ageDifMs = Date.now() - birthDate.getTime();
      const ageDate = new Date(ageDifMs);
      return Math.abs(ageDate.getUTCFullYear() - 1970);
    },
    async fetchRecords() {
      try {
        const response = await medicalRecordService.getMedicalRecords();
        this.records = response.data.body;
      } catch (error) {
        console.error('Error fetching patients:', error);
      }
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchRecords();
    },
    filterRecords() {
      this.fetchRecords();
    },
  },
};
</script>

<style lang="scss" scoped>
.text-bold {
  font-weight: bold;
}

.q-card-section {
  position: relative;
}

.q-row {
  display: flex;
  flex-wrap: nowrap;
  gap: 1rem;
}

.q-col-12 {
  flex: 1;
}

@media (max-width: 767px) {
  .flex-column-reverse-on-mobile {
    flex-direction: column-reverse;
  }
}

.editor {
  color: $primary;
}

.scrollable-records {
  max-height: 400px;
  overflow-y: auto;
}

.scrollable-column {
  max-height: 75vh;
  overflow-y: auto;
  padding-right: 1rem;
}
</style>
