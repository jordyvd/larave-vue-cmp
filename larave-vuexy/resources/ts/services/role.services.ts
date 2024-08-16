import api from '@/config/axios';
import { Role } from '@/models/role.models';
import { Get, Store } from './interfaces/role.interfaces.services';

const prefix = '/roles';

class RoleServices {
  async get(params: Get) {
    const { data } = await api.get(`${prefix}`, { params });
    data.data.data as Role[];
    return data.data;
  }

  async store(params: Store) {
    return await api.post(`${prefix}/store`, params);
  }

  async update(id: number, params: Store) {
    return await api.put(`${prefix}/update/${id}`, params);
  }

  async getSections() {
    const { data } = await api.get(`${prefix}/sections`);
    return data.data;
  }
}

export default new RoleServices();
