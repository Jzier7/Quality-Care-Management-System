<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h1 class="text-primary">Emergency Contact</h1>
        </q-toolbar-title>
      </q-toolbar>

      <div class="row justify-between items-center">
        <q-btn dense label="Add Emergency Contact" color="primary" @click="openAddEmergencyContactModal"
          style="text-transform: capitalize;" />
        <form @submit.prevent="fetchEmergencyContacts" class="q-gutter-md">
          <q-input rounded outlined dense v-model="search" placeholder="Search Emergency Contacts"
            @input="fetchEmergencyContacts" color="primary">
            <template v-slot:prepend>
              <q-icon name="search" />
            </template>
          </q-input>
        </form>
      </div>

      <div class="row cards-container">
        <div v-for="contact in emergencyContacts" :key="contact.id" class="col-12 col-md-3 card-item">
          <q-card>
            <div v-if="contact.files" class="q-mb-md">
              <img :src="getMediaURL(contact.files[0])" alt="Image" class="card-image" />
            </div>

            <q-card-section>
              <div class="text-weight-bold">{{ contact.name }}</div>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn flat icon="delete" color="red" @click="openDeleteAddEmergencyContactModal(contact)" />
            </q-card-actions>
          </q-card>
        </div>
      </div>

      <!-- Pagination Section -->
      <div class="row justify-center q-mt-md">
        <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
      </div>

      <AddEmergencyContactModal :fetchEmergencyContacts="fetchEmergencyContacts" />
      <DeleteEmergencyContactModal :fetchEmergencyContacts="fetchEmergencyContacts"
        :deleteData="emergencyContactData" />
    </div>
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import emergencyContactService from 'src/services/emergencyContactService';
import handleMedia from 'src/utils/mixin/handleMedia';
import _debounce from 'lodash/debounce';

export default {
  components: {
    AddEmergencyContactModal: defineAsyncComponent(() => import('components/Modals/EmergencyContact/AddEmergencyContact.vue')),
    DeleteEmergencyContactModal: defineAsyncComponent(() => import('components/Modals/EmergencyContact/DeleteEmergencyContact.vue')),
  },
  mixins: [handleMedia],
  data() {
    return {
      emergencyContactData: {},
      emergencyContacts: [],
      search: '',
      currentPage: 1,
      pageSize: 5,
      lastPage: 1,
      total: 0,
    };
  },
  watch: {
    search() {
      this.currentPage = 1;

      clearTimeout(this.debounceTimeout);

      this.debounceTimeout = setTimeout(() => {
        this.fetchEmergencyContacts();
      }, 1000);
    },
  },
  methods: {
    openAddEmergencyContactModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddEmergencyContactModal(true);
    },
    openDeleteAddEmergencyContactModal(contact) {
      this.emergencyContactData = contact;
      const modalStore = useModalStore();
      modalStore.setShowDeleteEmergencyContactModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchEmergencyContacts();
    },
    async fetchEmergencyContacts() {
      try {
        const response = await emergencyContactService.getPaginatedEmergencyContacts({
          page: this.currentPage,
          pageSize: this.pageSize,
          search: this.search,
        });

        this.emergencyContacts = response.data.body || [];
        this.total = response.data.details.total || 0;
        this.lastPage = Math.ceil(this.total / this.pageSize);
      } catch (error) {
        console.error('Error fetching emergency contacts:', error);
      }
    },
  },
  mounted() {
    this.fetchEmergencyContacts();
  },
};
</script>

<style scoped>
.card-item {
  padding: 10px 5px 0;
}

.card-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}
</style>
