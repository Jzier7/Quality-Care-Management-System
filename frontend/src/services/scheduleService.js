import { api } from 'src/boot/axios'

const scheduleService = {
  async getAllPatientSchedules(params = null) {
    const response = await api.get('/api/schedule/retrieve/patient', { params });
    return response;
  },

  async getAllWorkerSchedules(params = null) {
    const response = await api.get('/api/schedule/retrieve/worker', { params });
    return response;
  },

  async storeSchedule(data) {
    const response = await api.post('/api/schedule/store', data );
    return response;
  },

  async updateSchedule(data) {
    const response = await api.patch('/api/schedule/update', data );
    return response;
  },

  async deleteSchedule(data) {
    const response = await api.delete('/api/schedule/delete', { data });
    return response;
  },
};

export default scheduleService;
