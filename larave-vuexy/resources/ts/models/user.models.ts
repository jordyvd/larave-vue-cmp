import { Role } from './role.models';

export interface User {
  id: number;
  name: string;
  email: string;
  role: Role
}
