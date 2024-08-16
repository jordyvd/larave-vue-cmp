<script setup lang="ts">
import commercialPricesCommonsServices from "@/services/commercial/prices/commercial.prices.commons.services";
import commercialPricesEcuadorServices from "@/services/commercial/prices/commercial.prices.ecuador.services";
import { Get as GetParams } from "@/services/interfaces/commercial/prices/commercial.prices.ecuador.interfaces.services";
import { globalStores } from "@/stores/global.stores";
import { sectionStores } from "@/stores/section.stores";

const Actions = defineAsyncComponent(
  () => import("@/components/commercial/components/Actions.vue")
);

const Menu = defineAsyncComponent(
  () => import("@/components/commercial/components/Menu.vue")
);

onMounted(() => {
  getPricesEcuador();
});

const useGlobalStores = globalStores();

const props = defineProps<{
  section: string;
}>();

const useSectionStores = sectionStores();

const actions = await useSectionStores.getActionsForSection(
  props.section ?? ""
);

const isLoading = ref(true);

const perPage = ref(50);

const headers = ref<
  {
    title: string;
    id: string;
  }[]
>([]);

const prices = ref<any>([]);

const pagination = ref<{ total: number }>({
  total: 0,
});

const params = ref<GetParams>({
  page: 1,
  perPage: 50,
  search: null,
});

const getPricesEcuador = async () => {
  useGlobalStores.setShowLoading(true);
  const data = await commercialPricesEcuadorServices.get(params.value);

  prices.value = await data.data.data;

  headers.value = await data.headers;

  headers.value.forEach((header: any, index) => {
    header.sortable = false;
    header.minWidth = index == 1 ? "270px" : index == 2 ? "200px" : "100px";
  });

  pagination.value.total = data.data.total;
  useGlobalStores.setShowLoading(false);
};

const setSearch = async () => {
  params.value.page = 1;
  getPricesEcuador();
};

const changePage = (value: number) => {
  params.value.page = value ?? 1;
  getPricesEcuador();
};
</script>
<template>
  <div v-if="useSectionStores.getAccessForAction(actions, 1)">
    <VCard id="invoice-list">
      <VCardText
        class="d-flex justify-space-between align-center flex-wrap gap-4"
      >
        <div class="d-flex gap-4 align-center flex-wrap">
          <Actions
            :actions="actions"
            :params="params"
            :section="'ecuador'"
            @setLoading="useGlobalStores.setShowLoading($event)"
            @setReload="getPricesEcuador"
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
              @keydown.enter="setSearch"
            />
          </div>
        </div>
      </VCardText>
      <VDivider />
      <VDataTable
        :headers="headers"
        :items="prices"
        :items-per-page="perPage"
        height="70vh"
        fixed-header
      >
        <template #bottom>
          <TablePagination
            v-model:page="params.page"
            :items-per-page="params.perPage"
            :total-items="pagination.total"
            @update:page="changePage"
          >
            <!-- <template #></template> -->
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

.custom-loader {
  display: flex;
  animation: loader 1s infinite;
}

@keyframes loader {
  from {
    transform: rotate(0);
  }

  to {
    transform: rotate(360deg);
  }
}

.spacer {
  margin-top: -2px;
}
</style>
