import { api } from 'src/boot/axios'

const patientService = {
  async getPaginatedPatients(params = null) {
    const response = await api.get('/api/patient/retrieve/paginated', { params });
    return response;
  },

  async getAllPatients() {
    const response = await api.get('/api/patient/retrieve/all');
    return response;
  },

  async storePatient(data) {
    const response = await api.post('/api/patient/store', data );
    return response;
  },

  async updatePatient(data) {
    const response = await api.patch('/api/patient/update', data );
    return response;
  },

  async updatePatientStatus(data) {
    const response = await api.patch('/api/patient/update/status', data );
    return response;
  },

  async deletePatient(data) {
    const response = await api.delete('/api/patient/delete', { data });
    return response;
  },
};

export default patientService;
