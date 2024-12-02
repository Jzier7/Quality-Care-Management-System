import { defineStore } from 'pinia'

export const useModalStore = defineStore('modal', {
  state: () => ({
    showAddWorkerModal: false,
    showEditWorkerModal: false,
    showDeleteWorkerModal: false,

    showAddPatientModal: false,
    showEditPatientModal: false,
    showDeletePatientModal: false,

  }),
  actions: {

    setShowAddWorkerModal(params) {
      this.showAddWorkerModal = params;
    },
    setShowEditWorkerModal(params) {
      this.showEditWorkerModal = params;
    },
    setShowDeleteWorkerModal(params) {
      this.showDeleteWorkerModal = params;
    },

    setShowAddPatientModal(params) {
      this.showAddPatientModal = params;
    },
    setShowEditPatientModal(params) {
      this.showEditPatientModal = params;
    },
    setShowDeletePatientModal(params) {
      this.showDeletePatientModal = params;
    },

  },
})

