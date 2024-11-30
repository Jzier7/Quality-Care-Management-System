import { defineStore } from 'pinia'

export const useModalStore = defineStore('modal', {
  state: () => ({
    showAddWorkerModal: false,
    showEditWorkerModal: false,
    showDeleteWorkerModal: false,
    showViewWorkerModal: false,

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
    setShowViewWorkerModal(params) {
      this.showViewWorkerModal = params;
    },

  },
})

