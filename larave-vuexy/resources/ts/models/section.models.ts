import { Action } from './action.models';

export interface Section {
  id: number;
  name: string;
  title: string;
  description?: string | null;
  access: boolean;
  actions: Action[] | [];
  parent_id: number | null;
  children: Section[] | [];
}
