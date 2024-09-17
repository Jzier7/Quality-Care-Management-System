<template>
  <q-page class="q-pa-md flex flex-col lg:flex-row">

    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h1 class="text-dark text-3xl font-bold">Schedule Check up</h1>
      </q-toolbar-title>
    </q-toolbar>

    <div class="w-full lg:w-2/3 mb-6 lg:mb-0 lg:pr-6">
      <q-card flat bordered class="w-full h-full">
        <q-card-section>
          <h2 class="text-dark text-xl font-bold mb-4">Calendar</h2>
          <vue-cal
            ref="calendar"
            class="vue-cal"
            :events="calendarEvents"
            @event-click="handleEventClick"
            :config="calendarConfig"
          />
        </q-card-section>
      </q-card>
    </div>

    <div class="w-full lg:w-1/3">
      <q-card flat bordered class="w-full h-full">
        <q-card-section>
          <h2 class="text-dark text-xl font-bold mb-4">Appointments</h2>
          <div v-if="appointments.length === 0" class="text-center text-gray-500">
            No appointments scheduled.
          </div>
          <q-list>
            <q-item
              v-for="appointment in appointments"
              :key="appointment.id"
              class="q-mb-sm bg-light q-pa-sm rounded-lg hover:bg-gray-100"
            >
              <q-item-section>
                <q-item-label class="text-dark text-lg font-semibold">{{ appointment.title }}</q-item-label>
                <q-item-label class="text-dark caption">{{ appointment.date }}</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';

export default {
  name: 'ScheduleCheckup',
  components: {
    VueCal
  },
  data() {
    return {
      calendarEvents: [
        { name: 'Checkup', start: '2024-09-20T10:00:00', end: '2024-09-20T11:00:00' },
      ],
      appointments: [
        { id: 1, title: 'Annual Checkup', date: '2024-09-20 10:00 AM' },
        { id: 2, title: 'Follow-up Visit', date: '2024-09-25 2:00 PM' },
      ],
      calendarConfig: {
      }
    };
  },
  methods: {
    handleEventClick(event) {
      console.log('Event clicked:', event);
    },
  },
};
</script>

<style scoped>
.vue-cal {
  width: 100%;
  height: 600px; /* Adjust height as needed */
}

.q-card {
  overflow: hidden;
}

.q-card-section {
  padding: 16px; /* Adjust padding as needed */
}

.q-item {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
}
</style>

