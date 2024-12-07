import { api } from 'src/boot/axios'

const emergencyContactService = {
  async getPaginatedEmergencyContacts(params = null) {
    const response = await api.get('/api/emergencyContact/retrieve/paginated', { params });
    return response;
  },

  async getAllEmergencyContacts() {
    const response = await api.get('/api/emergencyContact/retrieve/all');
    return response;
  },

  async storeEmergencyContact(data) {
    const response = await api.post('/api/emergencyContact/store', data );
    return response;
  },

  async deleteEmergencyContact(data) {
    const response = await api.delete('/api/emergencyContact/delete', { data });
    return response;
  },
};

export default emergencyContactService;
