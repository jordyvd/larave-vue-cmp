
import { login as Ilogin } from '@/models/login.models';
import { router } from '@/plugins/1.router';
import authServices from '@/services/auth.services';

const login = async (pLogin: Ilogin) => {
  try {
    const data = await authServices.login(pLogin);
    localStorage.removeItem('token');
    localStorage.setItem('token', data.data.token);
    router.push({ path: "/" });
    return data.data;
  } catch (error) {
    localStorage.removeItem('token');
    return error;
  }
}

export default {
  login
}
