import { defineStore } from 'pinia';
import userService from 'src/services/userService';

export const useUserStore = defineStore('user', {
  state: () => ({
    userData: {},
  }),
  actions: {
    async fetchUser() {
      try {
        const response = await userService.getUser();
        this.userData = response.data.body;
      } catch (error) {
        console.error("Error fetching user: ", error);
      }
    },
    async updateWorker(data) {
      try {
        const response = await userService.updateWorker(data);
        this.fetchUser();
        return { success: true, response };
      } catch (error) {
        console.error("Error editing user: ", error);
        return { success: false, error: error.response.data };
      }
    },
    async updatePatient(data) {
      try {
        const response = await userService.updatePatient(data);
        this.fetchUser();
        return { success: true, response };
      } catch (error) {
        console.error("Error editing user: ", error);
        return { success: false, error: error.response.data };
      }
    },
    setUserData(data) {
      this.userData = data;
    },
    removeUser() {
      this.userData = {};
    }
  },
  persist: {
    storage: sessionStorage,
    pick: ['userData'],
  },
});

