import api from '@/config/axios';
import { login } from '../models/login.models';

const prefix = '/auth';

class authServices {
  async login(params: login) {
    const { data } = await api.post(`${prefix}/login`, params);
    return data;
  }

  async getUser() {
    const { data } = await api.get(`${prefix}/user`);
    return data;
  }

  async logout() {
    await api.post(`${prefix}/logout`);
  }
}

export default new authServices();
