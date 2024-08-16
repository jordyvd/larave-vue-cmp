<script lang="ts" setup>
import commercialPricesCommonsServices from "@/services/commercial/prices/commercial.prices.commons.services";
import { Get as GetParams } from "@/services/interfaces/commercial/prices/commercial.prices.commons.interfaces.services";
import { globalStores } from "@/stores/global.stores";
import { sectionStores } from "@/stores/section.stores";

const Table = defineAsyncComponent(
  () => import("@/components/commercial/components/Table.vue")
);

onMounted(() => {
  getPrices();
});

const props = defineProps<{
  section: string;
}>();

const useSectionStores = sectionStores();

const useGlobalStores = globalStores();

const actions = await useSectionStores.getActionsForSection(
  props.section ?? ""
);

const params = ref<GetParams>({
  page: 1,
  perPage: 50,
  search: null,
});

const headers = ref<
  {
    title: string;
    id: string;
  }[]
>([]);

const prices = ref<any>([]);

const listPricesOptions = ref<any>([]); //opciones seleccionadas guardadas en la base de datos

const pagination = ref<{ total: number }>({
  total: 0,
});

const getPrices = async () => {
  useGlobalStores.setShowLoading(true);
  const data = await commercialPricesCommonsServices.get("quo", params.value);

  listPricesOptions.value = await data.listPricesOptions;

  prices.value = await data.data.data;

  headers.value = await data.headers;

  pagination.value.total = data.data.total;

  await headers.value.forEach((header: any, index) => {
    header.sortable = false;
    header.minWidth = index == 1 || index == 2 ? "270px" : "100px";
  });

  useGlobalStores.setShowLoading(false);
};

const setSearch = async (paramsParam: GetParams) => {
  params.value = await paramsParam;
  params.value.page = 1;
  await getPrices();
};

const changePage = (value: number) => {
  params.value.page = value ?? 1;
  getPrices();
};
const setOptionSelected = async () => {
  getPrices();
};
</script>
<template>
  <div>
    <Table
      section="quo"
      :data="prices"
      :headers="headers"
      :listPricesOptions="listPricesOptions"
      :params="params"
      :pagination="pagination"
      :actions="actions"
      @setSearch="setSearch"
      @changePage="changePage"
    ></Table>
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
