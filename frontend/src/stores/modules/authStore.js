import { defineStore } from 'pinia'
import authService from "src/services/authService";
import { useUserStore } from './userStore';

export const useAuthStore = defineStore('auth', {
  actions: {
    async login(credentials) {
      const userStore = useUserStore();

      try {
        const response = await authService.login(credentials);
        userStore.setUserData(response.body);

        return {
          data: response.body,
          message: response.message,
          status: response.status
        };

      } catch (error) {
        console.error("Login failed:", error);

        return {
          data: null,
          message: error.response.data.message,
          status: error.status
        };

      }
    },

    async logout() {
      const userStore = useUserStore();

      try {
        const response = await authService.logout();
        userStore.removeUser();

        return {
          data: response.body,
          message: response.message
        };

      } catch (error) {
        console.error("Logout failed:", error);

        return {
          data: null,
          message: 'Logout failed'
        };
      }
    },

    async checkAuth() {
      const userStore = useUserStore();

      try {
        const response = await authService.checkAuth();
        userStore.setUserData(response.body.user);

        return response.body.user;

      } catch (error) {
        console.error("Remember me check failed:", error);
        return { isAuthenticated: false, viaRemember: false };
      }
    },
  }
});

