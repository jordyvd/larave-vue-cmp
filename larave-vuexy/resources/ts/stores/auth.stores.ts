import { User } from "@/models/user.models";
import authServices from "@/services/auth.services";

export const authStores = defineStore('auth', {
  state: () => ({
    user: {} as User
  }),
  actions: {
    async getUser() {
      const { data } = await authServices.getUser();
      this.user = data as User;
    }
  }
});
