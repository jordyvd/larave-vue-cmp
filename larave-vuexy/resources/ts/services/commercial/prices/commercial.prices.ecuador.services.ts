import api from '@/config/axios';
import { Get } from '@/services/interfaces/commercial/prices/commercial.prices.ecuador.interfaces.services';

const prefix = '/commercial/prices/ecuador';

const commercialPricesEcuadorServices = {
    async get(params: Get) {
        const { data } = await api.get(`${prefix}`, { params });
        return data.data;
    }
};

export default commercialPricesEcuadorServices;
