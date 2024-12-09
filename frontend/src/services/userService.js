import { api } from 'src/boot/axios'

const userService = {
  async getUser() {
    const response = await api.get('/api/user/me');
    return response;
  },

  async getUsers(params) {
    const response = await api.get('/api/users/retrieve/users', { params });
    return response;
  },

  async updateWorker(data) {
    const response = await api.patch('/api/users/update/worker', data);
    return response;
  },

  async updatePatient(data) {
    const response = await api.patch('/api/users/update/patient', data);
    return response;
  },

  async deleteUser(data) {
    const response = await api.delete('/api/users/delete', { data });
    return response;
  },
};

export default userService;
