import { api } from 'src/boot/axios'

const informationBoardService = {
  async getPaginatedInformationBoards(params = null) {
    const response = await api.get('/api/informationBoard/retrieve/paginated', { params });
    return response;
  },

  async getAllInformationBoards() {
    const response = await api.get('/api/informationBoard/retrieve/all');
    return response;
  },

  async storeInformationBoard(data) {
    const response = await api.post('/api/informationBoard/store', data );
    return response;
  },

  async deleteInformationBoard(data) {
    const response = await api.delete('/api/informationBoard/delete', { data });
    return response;
  },
};

export default informationBoardService;
