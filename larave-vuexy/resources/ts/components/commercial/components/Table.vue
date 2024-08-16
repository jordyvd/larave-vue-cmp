<script lang="ts" setup>
import { sectionStores } from "@/stores/section.stores";
import { globalStores } from "@/stores/global.stores";

const Actions = defineAsyncComponent(
  () => import("@/components/commercial/components/Actions.vue")
);

const Menu = defineAsyncComponent(
  () => import("@/components/commercial/components/Menu.vue")
);

const Alert = defineAsyncComponent(
  () => import("@/components/commercial/components/Alert.vue")
);

interface params {
  page: number;
  perPage: number;
  search: string | null;
}
const props = defineProps<{
  section: string;
  data: any;
  headers: any;
  params: params;
  pagination: {
    total: number;
  };
  actions: any;
  listPricesOptions: any;
}>();

const params: params = {
  page: 1,
  perPage: 50,
  search: "",
};

const emit = defineEmits<{
  (e: "setSearch", value: params): void;
  (e: "changePage", value: params): void;
}>();

onMounted(async () => {
  await setIsMobile();
});

const useGlobalStores = globalStores();

const useSectionStores = sectionStores();

const showAlert = ref<boolean>(false);

const itemSelected = ref<any>(null);

const currentItem = ref<any>(null);

const isMobile = ref<boolean>(false);

const setIsMobile = async () => {
  console.log(window.innerWidth);
  isMobile.value = await !(window.innerWidth >= 1030);
};
</script>
<template>
  <div v-if="useSectionStores.getAccessForAction(props.actions, 1)">
    <alert
      v-if="showAlert && isMobile"
      :item="currentItem"
      @close="showAlert = false"
    ></alert>
    <VCard id="invoice-list">
      <VCardText
        class="d-flex justify-space-between align-center flex-wrap gap-4"
      >
        <div class="d-flex gap-4 align-center flex-wrap">
          <Actions
            :actions="props.actions"
            :params="props.params"
            :section="props.section"
            @setLoading="useGlobalStores.setShowLoading($event)"
            @setReload="
              params.search = null;
              params.page = 1;
              emit('setSearch', params);
            "
          ></Actions>
        </div>
        <div class="d-flex align-center flex-wrap gap-4">
          <div class="invoice-list-filter">
            <AppTextField
              placeholder="Buscar ..."
              append-inner-icon="tabler-search"
              single-line
              hide-details
              dense
              outlined
              v-model="params.search"
              @keydown.enter="emit('setSearch', params)"
            />
          </div>
          <!-- ********** MENU ************ -->
          <Menu
            :actions="actions"
            :section="props.section"
            @setLoading="useGlobalStores.setShowLoading($event)"
            @optionSelected="emit('setSearch', params)"
            :listPricesOptions="props.listPricesOptions"
          ></Menu>
          <!-- ********************** -->
        </div>
      </VCardText>
      <VDivider />
      <VDataTable
        :headers="headers"
        :items="data"
        :items-per-page="props.params.perPage"
        height="70vh"
        fixed-header
      >
        <template #item.ARTICULO="{ item }">
          <div
            @click="
              showAlert = !(itemSelected == item.CODART);
              itemSelected = itemSelected == item.CODART ? null : item.CODART;
              currentItem = item;
            "
          >
            <span
              :class="
                isMobile && itemSelected == item.CODART && showAlert
                  ? 'text-primary'
                  : ''
              "
              >{{ item.ARTICULO }}</span
            >
          </div>
        </template>
        <template #bottom>
          <TablePagination
            v-model:page="props.params.page"
            :items-per-page="props.params.perPage"
            :total-items="props.pagination.total"
            @update:page="emit('changePage', $event)"
          >
          </TablePagination>
        </template>
      </VDataTable>
    </VCard>
  </div>
</template>
<style lang="scss" scope>
#invoice-list {
  .invoice-list-actions {
    inline-size: 8rem;
  }

  .invoice-list-filter {
    inline-size: 12rem;
  }
}
</style>
