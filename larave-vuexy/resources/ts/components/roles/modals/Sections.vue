<script setup lang="ts">
import { Section } from "@/models/section.models";
import { Store as Role } from "@/services/interfaces/role.interfaces.services";
import { StoreAccess } from "@/services/interfaces/section.interfaces.services";
import sectionServices from "@/services/section.services";
import { globalStores } from "@/stores/global.stores";

interface Props {
  isVisible: boolean;
  roleData?: Role;
}

interface Emit {
  (e: "submit"): void;
  (e: "closeModal"): void;
}

const emit = defineEmits<Emit>();

const props = withDefaults(defineProps<Props>(), {
  isVisible: false,
  roleData: () => ({
    id: 0,
    name: "",
    description: null,
  }),
});

onMounted(() => {
  getSections();
});

const useGlobalStores = globalStores();

const sections = ref<Section[]>([]);

const parents = ref<Section[]>([]);

const parentCurrent = ref<Section>();

const children = ref<Section[]>([]);

const isShowChildrenByChild = ref<boolean>(false);

const sectionSelected = ref<number>(0);

const accordionSelected = ref<number>(0);

const addedAccess = ref<
  { action_id: number; section_id: number; access: boolean }[]
>([]);

const getSections = async () => {
  useGlobalStores.setShowLoading(true);
  const data = await sectionServices.getRecursive(props.roleData.id ?? 0);
  parents.value = data.filter((section) => section.parent_id === null);
  sections.value = data;
  useGlobalStores.setShowLoading(false);
};

const showChildren = async (parent: Section) => {
  sectionSelected.value = parent.id;
  parentCurrent.value = parent;
  if (parent.children.length > 0) {
    children.value = parent.children;
  } else {
    children.value = [];
    children.value.push(parent);
  }
};

const showChildrenByChild = async (child: Section) => {
  parents.value =
    (await parentCurrent.value?.children.filter(
      (item) => item.children.length > 0
    )) ?? [];
  isShowChildrenByChild.value = true;
  await showChildren(child);
};

const resetParent = async () => {
  parents.value = await sections.value.filter(
    (section) => section.parent_id === null
  );
  children.value = [];
  isShowChildrenByChild.value = false;
};

const changeAction = (
  action_id: number,
  section_id: number,
  access: boolean
) => {
  const index = addedAccess.value.findIndex(
    (item) => item.action_id === action_id && item.section_id === section_id
  );

  if (index > -1) {
    addedAccess.value.splice(index, 1);
  }

  addedAccess.value.push({
    action_id: action_id,
    section_id: section_id,
    access: access,
  });
};

const saveAccess = async () => {
  useGlobalStores.setShowLoading(true);
  const params: StoreAccess = {
    role_id: props.roleData.id ?? 0,
    access: addedAccess.value,
  };
  await sectionServices.storeAccess(params);
  useGlobalStores.setShowLoading(false);
  emit("submit");
};

const closeModal = () => {
  emit("closeModal");
};

const openAccordion = (index: number) => {
  if (accordionSelected.value === index) {
    accordionSelected.value = -1;
    return;
  }
  accordionSelected.value = index;
};
</script>
<template>
  <div>
    <VDialog width="800" :model-value="props.isVisible" persistent>
      <DialogCloseBtn @click="closeModal()" />
      <VCard class="pa-sm-10 pa-2">
        <VBtn
          class="position-absolute"
          icon
          @click="resetParent"
          v-if="isShowChildrenByChild"
        >
          <VIcon icon="tabler-arrow-big-left-lines" />
          <VTooltip location="bottom" activator="parent"> Regresar </VTooltip>
        </VBtn>
        <h4 class="text-h4 text-center mb-2">
          {{ "Permisos" }}
        </h4>
        <p class="text-body-1 text-center mb-6">
          {{ "Personalizar accesos del rol" }} <b>{{ props.roleData.name }}</b>
        </p>
        <VRow class="content">
          <v-col cols="6" class="max-height scroll-custom border-right">
            <div v-for="(parent, index) in parents" :key="index">
              <v-alert
                class="mb-2 sections"
                @click="showChildren(parent)"
                :class="sectionSelected == parent.id ? 'section-selected' : ''"
              >
                <VIcon icon="tabler-arrow-badge-right-filled" />
                {{ parent.title }}
              </v-alert>
            </div>
          </v-col>
          <v-col cols="6" class="max-height scroll-custom">
            <div v-for="(child, index) in children" :key="index" class="mb-2">
              <v-alert
                :class="
                  child.children.length > 0 ? 'mb-2 sections' : 'cursor-pointer'
                "
                @click="
                  child.children.length > 0
                    ? showChildrenByChild(child)
                    : openAccordion(index)
                "
                v-if="child.children.length > 0 || child.actions.length > 0"
              >
                <VIcon
                  icon="tabler-arrow-badge-right-filled"
                  v-if="child.children.length > 0"
                />
                <template v-else>
                  <VIcon
                    icon="tabler-caret-down"
                    class="mt-negative"
                    v-if="accordionSelected == index"
                  />
                  <VIcon icon="tabler-caret-up" class="mt-negative" v-else />
                </template>
                {{ child.title }}
              </v-alert>
              <div
                v-for="(action, indexAction) in child.actions"
                :key="indexAction"
                v-show="accordionSelected == index"
              >
                <div class="demo-space-x">
                  <!-- ***** ACCIONES CON HIJOS ***** -->
                  <template v-if="action.children.length > 0">
                    <div>
                      <p class="ml-2 font-weight-bold text-uppercase">
                        {{ action.name }}
                      </p>
                      <div
                        v-for="(
                          childAction, indexChildAction
                        ) in action.children"
                        :key="indexChildAction"
                      >
                        <VCheckbox
                          :class="'ml-' + childAction.level"
                          :label="childAction.name"
                          :id="`action-child-${child.name}-${childAction.id}`"
                          v-model="childAction.access"
                          @change="
                            () =>
                              changeAction(
                                childAction.id,
                                child.id,
                                childAction.access
                              )
                          "
                          :disabled="
                            (!action.children[0].access &&
                              indexChildAction > 0) ||
                            (!child.actions[0].access && indexAction > 0)
                          "
                        />
                      </div>
                    </div>
                  </template>
                  <!-- ***** ACCIONES SIN HIJOS ***** -->
                  <template v-else>
                    <div>
                      <VCheckbox
                        :label="action.name"
                        :id="`action-${child.name}-${action.name}`"
                        v-model="action.access"
                        @change="
                          () => changeAction(action.id, child.id, action.access)
                        "
                        :disabled="!child.actions[0].access && indexAction > 0"
                      />
                    </div>
                  </template>
                </div>
              </div>
            </div>
            <div v-if="children.length == 0">
              <v-alert color="warning">
                <p class="text-center">No se encontró opciones</p>
              </v-alert>
            </div>
          </v-col>
          <VCol cols="12" class="d-flex flex-wrap justify-center gap-4 mt-4">
            <VBtn type="submit" @click="saveAccess"> Guardar </VBtn>
          </VCol>
        </VRow>
      </VCard>
    </VDialog>
  </div>
</template>
<style scoped>
.border-right {
  border-right: 1px solid #d8d8d8;
}

.sections:hover {
  background: rgb(var(--v-theme-primary));
  color: white;
  transition: 0.5s;
  cursor: pointer;
}

.section-selected {
  background: rgb(var(--v-theme-primary)) !important;
  color: white !important;
}

/* .content {
  padding: 5px;
  max-height: 60vh;
} */

p {
  margin-block-end: 0rem;
}

.max-height {
  overflow-y: auto;
  max-height: 50vh;
}
.mt-negative {
  margin-top: -2.5px;
}
</style>
