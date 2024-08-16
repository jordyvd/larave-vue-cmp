<script setup lang="ts">
import { User } from "@/models/user.models";
import {
  Get as GetParams,
  Store as UpdateParams,
} from "@/services/interfaces/user.interfaces.services";
import userServices from "@/services/user.services";
import { globalStores } from "@/stores/global.stores";
import { sectionStores } from "@/stores/section.stores";

const Add = defineAsyncComponent(() => import("./modals/Add.vue"));

const props = defineProps<{
  section: string;
}>();

const useSectionStores = sectionStores();
const useGlobalStores = globalStores();

const actions = await useSectionStores.getActionsForSection(
  props.section ?? ""
);

const perPage = ref(25);

const users = ref<User[]>([]);

const response = ref<{ count: number }>({
  count: 0,
});

const isModalVisible = ref(false);

const params = ref<GetParams>({
  page: 1,
  perPage: 50,
  search: null,
});

const userData = ref<UpdateParams>({
  id: null,
  name: "",
  email: "",
  password: "",
  role_id: null,
});

onMounted(() => {
  getUsers();
});

const headers = [
  { title: "NOMBRE", key: "name", sortable: false },
  { title: "CORREO", key: "email", sortable: false },
  { title: "ROL", key: "role.name", sortable: false },
  { title: "ACCIONES", key: "actions", sortable: false },
];

const getUsers = async () => {
  useGlobalStores.setShowLoading(true);
  const data = await userServices.get(params.value);
  users.value = data.data;
  response.value.count = data.total;
  useGlobalStores.setShowLoading(false);
};

const changePerPage = (value: number) => {
  params.value.perPage = value ?? 25;
  getUsers();
};

const openModal = () => {
  isModalVisible.value = true;
};

const saveUser = async () => {
  isModalVisible.value = false;
  userDataReset();
  await getUsers();
};

const showUser = async (value: User) => {
  openModal();
  userData.value.id = value.id;
  userData.value.name = value.name;
  userData.value.email = value.email;
  userData.value.role_id = value.role.id;
};

const closeModal = () => {
  isModalVisible.value = false;
  userDataReset();
};

const userDataReset = () => {
  userData.value = {
    id: null,
    name: "",
    email: "",
    password: "",
    role_id: null,
  };
};
</script>
<template>
  <div v-if="useSectionStores.getAccessForAction(actions, 1)">
    <Add
      v-if="isModalVisible"
      :isVisible="isModalVisible"
      @submit="saveUser"
      @closeModal="closeModal"
      :userData="userData"
      :isEdit="userData.id != null"
    />
    <VCard id="invoice-list">
      <VCardText
        class="d-flex justify-space-between align-center flex-wrap gap-4"
      >
        <div class="d-flex gap-4 align-center flex-wrap">
          <!-- *********** BTN ADD *********** -->
          <VBtn
            prepend-icon="tabler-plus"
            variant="tonal"
            @click="openModal()"
            v-if="useSectionStores.getAccessForAction(actions, 2)"
          >
            Agregar
          </VBtn>
          <!-- ********* END BTN ADD ********* -->
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
              @keydown.enter="getUsers"
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
        :items="users"
        :items-per-page="perPage"
        height="70vh"
        fixed-header
      >
        <template #item.actions="{ item }">
          <div class="d-flex gap-1">
            <!-- *** BTN EDIT *** -->
            <IconBtn
              v-if="useSectionStores.getAccessForAction(actions, 3)"
              @click="showUser(item)"
              color="warning"
              icon="tabler-edit"
              variant="tonal"
              rounded
            />
            <IconBtn
              v-else
              disabled
              color="warning"
              icon="tabler-edit"
              variant="tonal"
              rounded
            />
            <!-- *** BTN DELETE *** -->
            <IconBtn
              v-if="useSectionStores.getAccessForAction(actions, 4)"
              color="error"
              icon="tabler-trash"
              variant="tonal"
              rounded
            />
            <IconBtn
              v-else
              color="error"
              icon="tabler-trash"
              variant="tonal"
              rounded
              disabled
            />
            <!-- *** END BTN DELETE *** -->
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
