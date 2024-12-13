import { api } from 'src/boot/axios'

const dashboardService = {
  async getDashboardData() {
    const response = await api.get('/api/dashboard');
    return response;
  },
};

export default dashboardService;
