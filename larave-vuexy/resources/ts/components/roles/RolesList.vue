<script setup lang="ts">
import { Role } from "@/models/role.models";
import {
  Get as GetParams,
  Store as StoreRol,
} from "@/services/interfaces/role.interfaces.services";
import roleServices from "@/services/role.services";
import { globalStores } from "@/stores/global.stores";
import { sectionStores } from "@/stores/section.stores";

const props = defineProps<{
  section: string;
}>();

const useSectionStores = sectionStores();

const useGlobalStores = globalStores();

const Add = defineAsyncComponent(() => import("./modals/Add.vue"));

const Sections = defineAsyncComponent(() => import("./modals/Sections.vue"));

const actions = await useSectionStores.getActionsForSection(
  props.section ?? ""
);

const perPage = ref(25);

const roles = ref<Role[]>([]);

const response = ref<{ count: number }>({
  count: 0,
});

const isModalVisible = ref(false);

const isModalSectionsVisible = ref(false);

const params = ref<GetParams>({
  page: 1,
  perPage: 50,
  search: null,
});

const roleData = ref<StoreRol>({
  id: null,
  name: "",
  description: null,
});

onMounted(() => {
  getRoles();
});

const headers = [
  { title: "NOMBRE", key: "name", sortable: false },
  { title: "DESCRIPCION", key: "description", sortable: false },
  { title: "ACCIONES", key: "actions", sortable: false },
];

const getRoles = async () => {
  useGlobalStores.setShowLoading(true);
  const data = await roleServices.get(params.value);
  roles.value = data.data;
  response.value.count = data.total;
  useGlobalStores.setShowLoading(false);
};

const changePerPage = (value: number) => {
  params.value.perPage = value ?? 25;
  getRoles();
};

const openModal = () => {
  isModalVisible.value = true;
};

const openModalSection = () => {
  isModalSectionsVisible.value = true;
};

const refreshData = async () => {
  isModalVisible.value = false;
  isModalSectionsVisible.value = false;
  userDataReset();
  await getRoles();
};

const showRole = async (value: Role) => {
  roleData.value.id = value.id;
  roleData.value.name = value.name;
  roleData.value.description = value.description ?? null;
  openModal();
};

const showSections = async (value: Role) => {
  roleData.value.id = value.id;
  roleData.value.name = value.name;
  roleData.value.description = value.description ?? null;
  openModalSection();
};

const closeModal = () => {
  isModalVisible.value = false;
  userDataReset();
};

const userDataReset = () => {
  roleData.value = {
    id: null,
    name: "",
    description: null,
  };
};

const closeModalSections = () => {
  isModalSectionsVisible.value = false;
};
</script>
<template>
  <div v-if="useSectionStores.getAccessForAction(actions, 1)">
    <Add
      v-if="isModalVisible"
      :isVisible="isModalVisible"
      @submit="refreshData"
      @closeModal="closeModal"
      :roleData="roleData"
      :isEdit="roleData.id != null"
    />
    <Sections
      v-if="isModalSectionsVisible"
      :isVisible="isModalSectionsVisible"
      :roleData="roleData"
      @submit="refreshData"
      @closeModal="closeModalSections"
    />
    <VCard id="invoice-list">
      <VCardText
        class="d-flex justify-space-between align-center flex-wrap gap-4"
      >
        <div class="d-flex gap-4 align-center flex-wrap">
          <!-- <div class="d-flex align-center gap-2">
            <span>Show</span>
            <AppSelect :model-value="params.perPage" :items="[
              { value: 25, title: '25' },
              { value: 50, title: '50' },
              { value: 100, title: '100' },
            ]" style="inline-size: 5.5rem;" @update:model-value="changePerPage($event)" />
          </div> -->
          <!-- 👉 Create invoice -->
          <VBtn
            prepend-icon="tabler-plus"
            variant="tonal"
            @click="openModal"
            v-if="useSectionStores.getAccessForAction(actions, 2)"
          >
            Agregar
          </VBtn>
        </div>

        <div class="d-flex align-center flex-wrap gap-4">
          <!-- 👉 Search  -->
          <div class="invoice-list-filter">
            <AppTextField
              placeholder="Buscar ..."
              append-inner-icon="tabler-search"
              single-line
              hide-details
              dense
              outlined
              v-model="params.search"
              @keydown.enter="getRoles"
            />
          </div>

          <!-- 👉 Select status -->
          <!-- <div class="invoice-list-filter">
                        <AppSelect placeholder="Invoice Status" clearable clear-icon="tabler-x" single-line
                            :items="['Downloaded', 'Draft', 'Sent', 'Paid', 'Partial Payment', 'Past Due']" />
                    </div> -->
        </div>
      </VCardText>
      <VDivider />
      <VDataTable
        :headers="headers"
        :items="roles"
        :items-per-page="perPage"
        height="70vh"
        fixed-header
      >
        <template #item.actions="{ item }">
          <div class="d-flex gap-1">
            <!-- ---------- EDIT -->
            <IconBtn
              @click="showRole(item)"
              v-if="useSectionStores.getAccessForAction(actions, 3)"
              rounded
              variant="tonal"
              color="warning"
              icon="tabler-edit"
            >
            </IconBtn>
            <IconBtn
              v-else
              disabled
              rounded
              variant="tonal"
              color="warning"
              icon="tabler-edit"
            >
            </IconBtn>
            <!-- ---------- ADD ACCESS -->
            <IconBtn
              @click="showSections(item)"
              v-if="useSectionStores.getAccessForAction(actions, 6)"
              color="primary"
              icon="tabler-key"
              variant="tonal"
              rounded
            />
            <IconBtn
              v-else
              disabled
              color="primary"
              icon="tabler-key"
              variant="tonal"
              rounded
            />
          </div>
        </template>
        <!-- pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="params.page"
            :items-per-page="params.perPage"
            :total-items="response.count"
          >
            <!-- <template #></template> -->
          </TablePagination>
        </template>
      </VDataTable>
    </VCard>
  </div>
</template>
<style lang="scss">
#invoice-list {
  .invoice-list-actions {
    inline-size: 8rem;
  }

  .invoice-list-filter {
    inline-size: 12rem;
  }
}
</style>
