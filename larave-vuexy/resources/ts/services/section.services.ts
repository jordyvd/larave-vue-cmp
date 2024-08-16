import api from "@/config/axios";
import { Section } from "@/models/section.models";
import { StoreAccess } from "./interfaces/section.interfaces.services";

const prefix = "/sections";

class sectionServices {
  async get(role_id: number) {
    const { data } = await api.get(`${prefix}`, { params: { role_id } });
    return data.data as Section[];
  }

  async getRecursive(role_id: number) {
    const { data } = await api.get(`${prefix}/recursive`, { params: { role_id } });
    return data.data as Section[];
  }

  async storeAccess(params: StoreAccess) {
    await api.post(`${prefix}/store-access`, params);
  }
}

export default new sectionServices();
