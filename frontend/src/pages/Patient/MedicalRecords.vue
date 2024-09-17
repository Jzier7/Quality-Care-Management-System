<template>
  <q-page class="flex flex-col items-center q-pa-md">
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h2 class="text-dark text-3xl font-bold">Medical Records</h2>
      </q-toolbar-title>
    </q-toolbar>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-screen-xl w-full mt-6">

      <q-card flat bordered class="w-full shadow-lg prescription-card">
        <q-card-section class="text-center">
          <div>
            <h2 class="text-dark text-3xl font-bold">MD. JAMES LEWIS</h2>
            <p class="text-dark text-xl">Neurologist Doctor</p>
            <p class="text-dark text-base">No. 123456789</p>
          </div>

          <div class="form-fields mt-8 space-y-6">
            <div class="form-row flex justify-between text-lg">
              <span class="font-semibold text-dark">Patient's Name:</span>
              <span class="text-dark">Juan Dela Cruz</span>
            </div>
            <div class="form-row flex justify-between text-lg">
              <span class="font-semibold text-dark">Date of Birth:</span>
              <span class="text-dark">01/01/1974</span>
            </div>
            <div class="form-row flex justify-between text-lg">
              <span class="font-semibold text-dark">Date:</span>
              <span class="text-dark">09/16/2024</span>
            </div>
            <div class="form-row flex justify-between text-lg">
              <span class="font-semibold text-dark">Age:</span>
              <span class="text-dark">50</span>
            </div>
            <div class="form-row flex justify-between text-lg">
              <span class="font-semibold text-dark">Gender:</span>
              <span class="text-dark">Male</span>
            </div>
            <div class="form-row flex justify-between text-lg">
              <span class="font-semibold text-dark">Diagnosis:</span>
              <span class="text-dark">Hypertension</span>
            </div>
            <div class="form-row flex justify-between text-lg">
              <span class="font-semibold text-dark">Rx:</span>
              <span class="text-dark">Amlodipine 5mg</span>
            </div>
          </div>
          <q-card-section class="text-dark text-lg italic mt-10">
            Doctor's Signature
          </q-card-section>
        </q-card-section>
      </q-card>

      <q-card flat bordered class="w-full shadow-lg profile-card">
        <q-card-section class="text-center">
          <q-img src="https://via.placeholder.com/150" class="rounded-full mx-auto w-36 h-36 mb-8" />
          <div>
            <h3 class="text-dark text-2xl font-bold">Juan Dela Cruz</h3>
            <p class="text-dark text-lg">Age: 50</p>
            <p class="text-dark text-lg">Address: Babag, Lapu-Lapu City</p>
            <p class="text-dark text-lg">Emergency Contact: 09123456789</p>
          </div>
        </q-card-section>
      </q-card>

    </div>
  </q-page>
</template>

<script>
export default {
  name: 'MedicalRecords',
};
</script>

<style scoped lang="scss">
.prescription-card {
  @apply p-8 bg-white rounded-lg min-w-[450px];
}

.profile-card {
  @apply p-8 bg-white rounded-lg min-w-[350px];
}
</style>
