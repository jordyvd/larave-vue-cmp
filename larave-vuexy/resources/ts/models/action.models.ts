export interface Action {
  id: number;
  name: string;
  description?: string | null;
  access: boolean;
  children: Action[] | [];
  level: number
}
