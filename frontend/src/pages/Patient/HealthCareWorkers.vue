<template>
  <q-page class="q-pa-md flex flex-col items-center">
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h1 class="text-dark text-3xl font-bold">Healthcare Worker Information</h1>
      </q-toolbar-title>
    </q-toolbar>

    <q-card flat bordered class="bg-white p-8 w-full max-w-screen-xl mt-6" v-if="firstRecord">
      <q-card-section class="flex justify-center mb-6">
        <img :src="placeholderImage" alt="Healthcare Worker Picture" class="rounded-full w-36 h-36" />
      </q-card-section>

      <q-card-section class="text-center text-dark">
        <h2 class="text-dark text-2xl font-bold mb-2">
          {{ firstRecord.healthcare_worker?.user?.first_name }} {{ firstRecord.healthcare_worker?.user?.last_name }}
        </h2>
        <p class="text-dark text-lg mb-2">Specialization: {{ firstRecord.healthcare_worker?.position.name }}</p>
        <p class="text-dark text-lg mb-4">License #: {{ firstRecord.healthcare_worker?.license_number }}</p>

        <div class="space-y-4">
          <div class="flex justify-between text-lg">
            <span class="font-semibold text-dark">Email:</span>
            <span class="text-dark">{{ firstRecord.healthcare_worker?.user?.email || 'N/A' }}</span>
          </div>
          <div class="flex justify-between text-lg">
            <span class="font-semibold text-dark">Location:</span>
            <span class="text-dark">Lapu-Lapu District Hospital</span>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import medicalRecordService from 'src/services/medicalRecordService';

export default {
  name: 'HealthCareWorker',
  data() {
    return {
      records: [],
      placeholderImage: 'https://via.placeholder.com/150',
    };
  },
  computed: {
    firstRecord() {
      return this.records.length > 0 ? this.records[0] : null;
    },
  },
  mounted() {
    this.fetchRecords();
  },
  methods: {
    async fetchRecords() {
      try {
        const response = await medicalRecordService.getMedicalRecords();
        this.records = response.data.body;
      } catch (error) {
        console.error('Error fetching records:', error);
      }
    },
  },
};
</script>
