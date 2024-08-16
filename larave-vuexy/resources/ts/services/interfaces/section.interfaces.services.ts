interface AccessItem {
  section_id: number;
  action_id: number;
  access: boolean;
}

export interface StoreAccess {
  access: AccessItem[];
  role_id: number;
}
