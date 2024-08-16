import api from '@/config/axios';
import { Get } from '@/services/interfaces/commercial/prices/commercial.prices.commons.interfaces.services';

const prefix = '/commercial/prices/commons';
const prefixGlobal = '/commercial/prices';

const commercialPricesCommonsServices = {
  async refreshData() {
    await api.post(`${prefix}/refresh-data`);
  },

  async getLastRefresh() {
    const { data } = await api.get(`${prefix}/last-refresh`);
    return data.data;
  },

  async get(section: string, params: Get) {
    const { data } = await api.get(`${prefixGlobal}/${section}`, { params });
    return data.data;
  },

  async exportToExcel(section: string, params: Get) {
    const { data } = await api.post(`${prefixGlobal}/${section}/export-to-excel`, { search: params.search, optionLpPricesSelected: params.optionLpPricesSelected }, { responseType: 'blob' });
    return data;
  },

  async getListPrices(section: string) {
    const { data } = await api.get(`${prefixGlobal}/${section}/list-prices`);
    return data.data;
  },

  async storeListPricesPublic(section: string, listPrices: any) {
    await api.post(`${prefixGlobal}/${section}/store-list-prices-public`, { listPrices });
  },

  async storeOptionListPricesSelected(company: string, optionLpPricesSelected: number) {
    await api.post(`${prefix}/store-option-list-prices-selected-by-user`, { company, option_selected: optionLpPricesSelected });
  },

  async getOptionListPricesSelected(company: string) {
    const { data } = await api.get(`${prefix}/get-option-list-prices-selected-by-user`, { params: { company } });
    return data.data;
  },
}

export default commercialPricesCommonsServices;

