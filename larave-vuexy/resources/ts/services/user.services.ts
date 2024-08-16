import api from '@/config/axios';
import { Role } from '../models/role.models';
import { User } from '../models/user.models';
import { Get, Store } from './interfaces/user.interfaces.services';

const prefix = '/users';

class userServices {
  async get(params: Get) {
    const { data } = await api.get(`${prefix}`, { params });
    data.data.data as User[];
    return data.data;
  }

  async store(params: Store) {
    const { data } = await api.post(`${prefix}/store`, params);
    return data;
  }

  async update(id: number, params: Store) {
    const { data } = await api.put(`${prefix}/update/${id}`, params);
    return data;
  }

  async getRoles() {
    const { data } = await api.get(`${prefix}/roles`);
    data.data.data as Role[];
    return data.data;
  }
}

export default new userServices();
