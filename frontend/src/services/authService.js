import { api } from 'src/boot/axios'

const authService = {
  async login(form) {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/api/auth/login', form);
    return response.data;
  },

  async checkAuth() {
    const response = await api.get('/api/auth/check');
    return response.data;
  },

  async register(form) {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/api/auth/register', form);
    return response.data;
  },

  async forgotPassword(form) {
    const response = await api.post('/api/auth/forgot-password', form);
    return response.data;
  },

  async updatePassword(form) {
    const response = await api.post('/api/auth/update-password', form);
    return response.data;
  },

  async logout() {
    const response = await api.post('/api/auth/logout');
    return response.data;
  },
};

export default authService;
