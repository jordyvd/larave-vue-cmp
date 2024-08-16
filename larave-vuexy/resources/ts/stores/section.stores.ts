import sectionServices from '@/services/section.services';
import { Section } from '../models/section.models';

export const sectionStores = defineStore('section', {
  state: () => ({
    sections: [] as Section[]
  }),
  actions: {
    async getSections(role_id: number) {
      const data = await sectionServices.get(role_id);
      this.sections = data
    },

    validateAccessSection(section_name: String) {
      const index = this.sections.findIndex(section => section.name == section_name);
      if (index !== -1) {
        return this.sections[index].access
      } else {
        return false;
      }
    },

    getActionsForSection(section_name: String) {
      const index = this.sections.findIndex(section => section.name == section_name);
      if (index !== -1) {
        const actions = this.sections[index].actions;
        return actions.filter(action => action.access).map(action => action.id);
      } else {
        return [];
      }
    },

    getAccessForAction(actions: number[], action_id: number, _: string | null = null) {
      return actions.includes(action_id);
    }
  }
});
