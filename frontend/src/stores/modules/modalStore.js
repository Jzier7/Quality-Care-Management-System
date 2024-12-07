import { defineStore } from 'pinia'

export const useModalStore = defineStore('modal', {
  state: () => ({
    showAddWorkerModal: false,
    showEditWorkerModal: false,
    showDeleteWorkerModal: false,

    showAddPatientModal: false,
    showEditPatientModal: false,
    showDeletePatientModal: false,
    showViewPatientModal: false,

    showAddEmergencyContactModal: false,
    showDeleteEmergencyContactModal: false,

    showAddInformationBoardModal: false,
    showDeleteInformationBoardModal: false,

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
    setShowViewPatientModal(params) {
      this.showViewPatientModal = params;
    },

    setShowAddEmergencyContactModal(params) {
      this.showAddEmergencyContactModal = params;
    },
    setShowDeleteEmergencyContactModal(params) {
      this.showDeleteEmergencyContactModal = params;
    },

    setShowAddInformationBoardModal(params) {
      this.showAddInformationBoardModal = params;
    },
    setShowDeleteInformationBoardModal(params) {
      this.showDeleteInformationBoardModal = params;
    },

  },
})

