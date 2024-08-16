import { router } from "@/plugins/1.router";
import { globalStores } from "@/stores/global.stores";
import axios from "axios";

const getBaseUrl = "/api/";

const api = axios.create({
  baseURL: getBaseUrl,
  withCredentials: false,
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
    // "Content-Type": "multipart/form-data",
  },
});

api.interceptors.request.use(
  (config: any) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers["Authorization"] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

api.interceptors.response.use(
  (response) => {
    return response;
  },
  async (error) => {
    const {
      status,
      data: { message },
    } = error.response;

    if ([500, 400, 404, 409].includes(status)) {
      const useGlobalStores = globalStores();
      useGlobalStores.setShowDialogError(true, message);
      useGlobalStores.setShowLoading(false);
    }

    if (status == 401) {
      const useGlobalStores = globalStores();
      useGlobalStores.setShowLoading(false);
      //remove token
      localStorage.removeItem("token");

      router.push({ name: "login", replace: true });
    }

    return Promise.reject(error);
  }
);

export default api;
