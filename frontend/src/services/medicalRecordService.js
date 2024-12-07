import { api } from 'src/boot/axios'

const medicalRecordService = {
  async getPaginatedMedicalRecords(params) {
    const response = await api.get('/api/medicalRecord/retrieve/paginated', { params });
    return response;
  },

  async getAllMedicalRecords() {
    const response = await api.get('/api/medicalRecord/retrieve/all');
    return response;
  },

  async storeMedicalRecord(data) {
    const response = await api.post('/api/medicalRecord/store', data );
    return response;
  },

  async deleteMedicalRecord(data) {
    const response = await api.delete('/api/medicalRecord/delete', { data });
    return response;
  },
};

export default medicalRecordService;
