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
          <vue-cal ref="calendar" class="vue-cal" :events="calendarEvents" @event-click="handleEventClick"
            :disable-views="['years', 'year', 'week']" :time-from="7 * 60" :time-to="20 * 60" :time-step="30" />
        </q-card-section>
      </q-card>
    </div>

    <div class="w-full lg:w-1/3">
      <q-card flat bordered class="w-full h-full">
        <q-card-section>
          <h2 class="text-dark text-xl font-bold mb-4">Appointments</h2>
          <div class="row justify-between items-center q-pb-md">
            <q-btn flat round icon="add" color="primary" @click="openAddScheduleModal" />
            <form @submit.prevent="filterSchedules" class="q-gutter-md">
              <q-input rounded outlined dense v-model="search" placeholder="Search Appointments"
                @input="filterSchedules" color="primary">
                <template v-slot:prepend>
                  <q-icon name="search" />
                </template>
              </q-input>
            </form>
          </div>
          <div v-if="upcomingAppointments.length === 0" class="text-center text-gray-500 q-pt-md">
            No appointments scheduled.
          </div>
          <q-list>
            <q-item v-for="appointment in upcomingAppointments" :key="appointment.id"
              class="q-mb-sm bg-light q-pa-sm rounded-lg hover:bg-gray-100">
              <q-item-section class="q-pa-sm">
                <q-item-label class="text-dark text-lg font-semibold">{{ appointment.title }}</q-item-label>
                <q-item-label class="text-dark caption">{{ formatEventDateRange(appointment.start, appointment.end)
                  }}</q-item-label>
              </q-item-section>

              <q-item-section side>
                <div class="row items-center">
                  <q-btn rounded flat icon="edit" @click="openEditScheduleModal(appointment)" color="secondary" />
                    <q-btn rounded flat icon="delete" @click="openDeleteScheduleModal(appointment)" color="negative" />
                </div>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card-section>
      </q-card>
    </div>

    <AddScheduleModal :fetchSchedules="fetchSchedules" />
    <EditScheduleModal :fetchSchedules="fetchSchedules" :editData="scheduleData" />
    <DeleteScheduleModal :fetchSchedules="fetchSchedules" :deleteData="scheduleData" />
  </q-page>
</template>

<script>
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';
import { defineAsyncComponent } from 'vue';
import scheduleService from 'src/services/scheduleService';
import handleDateTime from 'src/utils/mixin/handleDateTime';
import { useModalStore } from 'src/stores/modules/modalStore';

export default {
  name: 'ScheduleCheckup',
  components: {
    VueCal,
    AddScheduleModal: defineAsyncComponent(() => import('components/Modals/Schedule/AddSchedule.vue')),
    EditScheduleModal: defineAsyncComponent(() => import('components/Modals/Schedule/EditSchedule.vue')),
    DeleteScheduleModal: defineAsyncComponent(() => import('components/Modals/Schedule/DeleteSchedule.vue')),
  },
  mixins: [handleDateTime],
  data() {
    return {
      calendarEvents: [],
      upcomingAppointments: [],
      scheduleData: {},
      search: '',
      orderBy: 'asc',
    };
  },
  watch: {
    search() {
      this.currentPage = 1;

      clearTimeout(this.debounceTimeout);

      this.debounceTimeout = setTimeout(() => {
        this.fetchPatients();
      }, 1000);
    },
  },
  methods: {
    handleEventClick(event) {
      console.log('Event clicked:', event);
    },
    filterSchedules() {
      this.fetchSchedules();
    },
    openAddScheduleModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddScheduleModal(true);
    },
    openEditScheduleModal(editData) {
      this.scheduleData = editData;
      const modalStore = useModalStore();
      modalStore.setShowEditScheduleModal(true);
    },
    openDeleteScheduleModal(deleteData) {
      this.scheduleData = deleteData;
      const modalStore = useModalStore();
      modalStore.setShowDeleteScheduleModal(true);
    },
    async fetchSchedules() {
      try {
        const response = await scheduleService.getAllWorkerSchedules({
          search: this.search,
          orderBy: this.orderBy
        });
        this.calendarEvents = response.data.body.all_schedules;
        this.upcomingAppointments = response.data.body.upcoming_schedules;
      } catch (error) {
        console.error('Error fetching schedules:', error);
      }
    },
  },
  mounted() {
    this.fetchSchedules();
  },
};
</script>

<style scoped>
.vue-cal {
  width: 100%;
  height: 600px;
}

.q-card {
  overflow: hidden;
}

.q-card-section {
  padding: 16px;
}

.q-item {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
}
</style>
