<template>
  <q-page padding>
    <q-card class="q-my-lg bg-primary" flat bordered>
      <q-card-section>
        <q-toolbar class="q-pa-none">
          <q-toolbar-title class="text-white">
            <h2>Hi! Admin 👋</h2>
            <p class="text-sm">Here's a quick look at today's updates.</p>
          </q-toolbar-title>
        </q-toolbar>
      </q-card-section>
    </q-card>

    <div class="q-gutter-md row items-stretch">
      <div class="col q-col-md-6">
        <q-card flat bordered class="full-height">
          <q-card-section>
            <div class="row items-center justify-center full-height">
              <q-icon name="local_hospital" :size="'5rem'" class="q-mr-lg" color="primary" />
              <div>
                <div class="text-h6 text-primary"><strong>Patients Overview</strong></div>
                <div class="text-subtitle1 text-grey-7">{{ patient.total }} Total Patients</div>
                <div class="q-mt-sm">
                  <div class="text-caption text-grey-6">Admitted: <span class="text-primary">{{ patient.admitted
                      }}</span></div>
                  <div class="text-caption text-grey-6">Discharged: <span class="text-primary">{{ patient.discharged
                      }}</span></div>
                  <div class="text-caption text-grey-6">Under Treatment: <span class="text-primary">{{
                    patient.under_treatment }}</span></div>
                  <div class="text-caption text-grey-6">Transferred: <span class="text-primary">{{ patient.transferred
                      }}</span></div>
                </div>
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col q-col-md-6">
        <q-card flat bordered class="full-height">
          <q-card-section>
            <div class="row items-center justify-center full-height">
              <q-icon name="medical_services" :size="'5rem'" class="q-mr-lg" color="primary" />
              <div>
                <div class="text-h6 text-primary"><strong>Healthcare Staff</strong></div>
                <div class="text-subtitle1 text-grey-7">{{ worker.total }} Total Staff</div>
                <div class="q-mt-sm">
                  <div v-for="(count, position) in worker.details" :key="position" class="text-caption text-grey-6">
                    {{ position }}: <span class="text-primary">{{ count }}</span>
                  </div>
                </div>
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <div class="col q-mt-md">
      <q-card flat bordered class="full-height">
        <q-card-section>
          <div class="row text-h6 text-primary justify-center"><strong>Users Added Today</strong></div>
          <q-table :rows="users" :columns="columns" row-key="id" flat v-if="users.length > 0">
            <template v-slot:header="props">
              <q-tr :props="props">
                <q-th v-for="col in props.cols" :key="col.name" :props="props" class="text-primary text-bold">
                  {{ col.label }}
                </q-th>
              </q-tr>
            </template>

            <template v-slot:body="props">
              <q-tr :props="props">
                <q-td v-for="col in props.cols" :key="col.name" :props="props" class="text-grey-8">
                  {{ props.row[col.name] }}
                </q-td>
              </q-tr>
            </template>
          </q-table>
          <div v-else class="text-center text-grey-6">No data available</div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import dashboardService from 'src/services/dashboardService';

export default {
  name: 'AdminDashboard',
  data() {
    return {
      users: [],
      patient: [],
      worker: [],
      columns: [
        { name: 'first_name', label: 'First Name', align: 'center' },
        { name: 'middle_name', label: 'Middle Name', align: 'center' },
        { name: 'last_name', label: 'Last Name', align: 'center' },
        { name: 'role', label: 'Role', align: 'center' },
      ],
    };
  },
  methods: {
    async fetchData() {
      try {
        const response = await dashboardService.getDashboardData();
        this.users = response.data.body.users.map(user => ({
          ...user,
          role: user.role?.name || null
        }));

        this.patient = response.data.body.patients;
        this.worker = response.data.body.healthcare_worker;

      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style lang="scss" scoped>
.scroll {
  max-height: 150px;
  overflow-y: auto;
}

.text-bold {
  font-weight: bold;
}
</style>
