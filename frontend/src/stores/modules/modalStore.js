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

    showAddRecordModal: false,
    showDeleteRecordModal: false,

    showAddEmergencyContactModal: false,
    showDeleteEmergencyContactModal: false,

    showAddInformationBoardModal: false,
    showDeleteInformationBoardModal: false,

    showAddScheduleModal: false,
    showEditScheduleModal: false,
    showDeleteScheduleModal: false,

    showAddPositionModal: false,
    showEditPositionModal: false,
    showDeletePositionModal: false,

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

    setShowAddRecordModal(params) {
      this.showAddRecordModal = params;
    },
    setShowDeleteRecordModal(params) {
      this.showDeleteRecordModal = params;
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

    setShowAddScheduleModal(params) {
      this.showAddScheduleModal = params;
    },
    setShowEditScheduleModal(params) {
      this.showEditScheduleModal = params;
    },
    setShowDeleteScheduleModal(params) {
      this.showDeleteScheduleModal = params;
    },

    setShowAddPositionModal(params) {
      this.showAddPositionModal = params;
    },
    setShowEditPositionModal(params) {
      this.showEditPositionModal = params;
    },
    setShowDeletePositionModal(params) {
      this.showDeletePositionModal = params;
    },

  },
})

