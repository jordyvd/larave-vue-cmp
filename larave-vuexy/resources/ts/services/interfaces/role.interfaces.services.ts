export interface Get {
  search: string | null;
  page: number;
  perPage: number;
}

export interface Store {
  id: number | null;
  name: string;
  description: string | null;
}
