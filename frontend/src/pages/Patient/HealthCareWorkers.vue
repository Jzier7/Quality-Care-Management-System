<template>
  <q-page class="q-pa-md flex flex-col items-center">
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h1 class="text-dark text-3xl font-bold">Healthcare Worker Information</h1>
      </q-toolbar-title>
    </q-toolbar>

    <q-card flat bordered class="bg-white p-8 w-full max-w-screen-xl mt-6">
      <q-card-section class="flex justify-center mb-6">
        <img src="https://via.placeholder.com/150" alt="Healthcare Worker Picture" class="rounded-full w-36 h-36"/>
      </q-card-section>

      <q-card-section class="text-center text-dark">
        <h2 class="text-dark text-2xl font-bold mb-2">Dr. Jane Smith</h2>
        <p class="text-dark text-lg mb-2">Specialization: Cardiology</p>
        <p class="text-dark text-lg mb-4">Hospital ID: 987654321</p>

        <div class="space-y-4">
          <div class="flex justify-between text-lg">
            <span class="font-semibold text-dark">Experience:</span>
            <span class="text-dark">10 years</span>
          </div>
          <div class="flex justify-between text-lg">
            <span class="font-semibold text-dark">Contact:</span>
            <span class="text-dark">(123) 456-7890</span>
          </div>
          <div class="flex justify-between text-lg">
            <span class="font-semibold text-dark">Email:</span>
            <span class="text-dark">janesmith@hospital.com</span>
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
export default {
  name: 'HealthCareWorker',
};
</script>

