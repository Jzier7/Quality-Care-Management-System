import { api } from 'src/boot/axios'

const workerService = {
  async getPaginatedWorkers(params = null) {
    const response = await api.get('/api/worker/retrieve/paginated', { params });
    return response;
  },

  async getAllWorkers() {
    const response = await api.get('/api/worker/retrieve/all');
    return response;
  },

  async storeWorker(data) {
    const response = await api.post('/api/worker/store', data );
    return response;
  },

  async updateWorker(data) {
    const response = await api.patch('/api/worker/update', data );
    return response;
  },

  async deleteWorker(data) {
    const response = await api.delete('/api/worker/delete', { data });
    return response;
  },
};

export default workerService;
