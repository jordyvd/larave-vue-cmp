<script setup lang="ts">
import commercialPricesCommonsServices from "@/services/commercial/prices/commercial.prices.commons.services";
import { sectionStores } from "@/stores/section.stores";

onMounted(async () => {
  emit("setLoading", true);
  getOptionSelected();
  getListPrices();
});

const useSectionStores = sectionStores();

const props = defineProps<{
  actions: number[];
  section: string;
  listPricesOptions: any[];
}>();

const emit = defineEmits<{
  (e: "optionSelected"): void;
  (e: "setLoading", value: boolean): void;
}>();

const listPrices = ref<
  {
    id: number;
    title: string;
  }[]
>([]);

const listPricesPublicSales = ref<number[]>([]);

const listPricesPublicAdvisors = ref<number[]>([]);

const optionLpPricesSelected = ref<number>(0);

watch(
  () => props.listPricesOptions,
  (newVal, oldVal) => {
    setDataInListPricesPublic(newVal);
  }
);

// const optionLpPricesSelected = ref<number>(
//   ["1", "2", "3"].includes(
//     localStorage.getItem(`optionLpPricesSelected-${props.section}`) ?? ""
//   )
//     ? parseInt(
//         localStorage.getItem(`optionLpPricesSelected-${props.section}`) || "0"
//       )
//     : 0
// );

const setDataInListPricesPublic = async (listPricesOptions: any) => {
  listPricesPublicSales.value = await listPricesOptions
    .filter((item: any) => item.option == 1)
    .map((item: any) => item.list_price_id);

  listPricesPublicAdvisors.value = await listPricesOptions
    .filter((item: any) => item.option == 2)
    .map((item: any) => item.list_price_id);
};

const getListPrices = async () => {
  emit("setLoading", true);

  const data = await commercialPricesCommonsServices.getListPrices(
    props.section
  );

  listPrices.value = await data;
};

const saveOptions = async () => {
  const listPricesPublic: { id: number; option: number }[] = [];

  listPricesPublicSales.value.forEach((item) =>
    listPricesPublic.push({ id: item, option: 1 })
  );

  listPricesPublicAdvisors.value.forEach((item) =>
    listPricesPublic.push({ id: item, option: 2 })
  );

  await commercialPricesCommonsServices.storeListPricesPublic(
    props.section,
    listPricesPublic
  );
};

const setOptionSelected = async () => {
  emit("setLoading", true);

  await commercialPricesCommonsServices.storeOptionListPricesSelected(
    props.section,
    optionLpPricesSelected.value
  );

  await saveOptions();

  emit("optionSelected");

  emit("setLoading", false);
};

const getOptionSelected = async () => {
  emit("setLoading", true);
  const selected =
    await commercialPricesCommonsServices.getOptionListPricesSelected(
      props.section
    );
  optionLpPricesSelected.value = selected;
  emit("setLoading", false);
};
</script>

<template>
  <VMenu :close-on-content-click="false">
    <template #activator="{ props }">
      <VBtn
        v-bind="props"
        color="secondary"
        variant="tonal"
        icon="tabler-caret-down"
        rounded
      />
    </template>

    <VCard max-width="400" min-width="400">
      <VList>
        <VListItem
          title="Menú"
          subtitle="Configuración de filtros"
          class="mx-0"
        />
      </VList>

      <VDivider />

      <VCardText class="mt-2">
        <div
          v-if="useSectionStores.getAccessForAction(actions, 10, 'LP. VENTAS')"
          class="select-radio-container mb-4"
        >
          <label>
            <input type="radio" :value="1" v-model="optionLpPricesSelected" />
            <span class="font-weight-bold ml-1">LP. VENTAS</span>
          </label>
          <v-select
            v-model="listPricesPublicSales"
            :items="listPrices"
            item-title="title"
            item-value="id"
            multiple
            :disabled="
              !useSectionStores.getAccessForAction(
                actions,
                11,
                'EDITAR LP. VENTAS'
              )
            "
          >
            <template v-slot:selection="{ item, index }">
              <v-chip v-if="index < 2">
                <span>{{ item.title }}</span>
              </v-chip>
              <span
                v-if="index === 2"
                class="text-grey text-caption align-self-center"
              >
                (+{{ listPricesPublicSales.length - 2 }} otros)
              </span>
            </template>
          </v-select>
        </div>

        <div
          v-if="
            useSectionStores.getAccessForAction(actions, 13, 'LP. ASESORES')
          "
          class="select-radio-container mb-4"
        >
          <label>
            <input type="radio" :value="2" v-model="optionLpPricesSelected" />
            <span class="font-weight-bold ml-1">LP. ASESORES</span>
          </label>
          <v-select
            v-model="listPricesPublicAdvisors"
            :items="listPrices"
            item-title="title"
            item-value="id"
            multiple
            class="mb-4"
            :disabled="
              !useSectionStores.getAccessForAction(
                actions,
                14,
                'EDITAR LP. ASESORES'
              )
            "
          >
            <template v-slot:selection="{ item, index }">
              <v-chip v-if="index < 2">
                <span>{{ item.title }}</span>
              </v-chip>
              <span
                v-if="index === 2"
                class="text-grey text-caption align-self-center"
              >
                (+{{ listPricesPublicAdvisors.length - 2 }} otros)
              </span>
            </template>
          </v-select>
        </div>

        <div
          v-if="useSectionStores.getAccessForAction(actions, 15, 'LP. TODOS')"
          class="select-radio-container"
        >
          <label>
            <input type="radio" :value="3" v-model="optionLpPricesSelected" />
            <span class="font-weight-bold ml-1">LP. TODOS</span>
          </label>
        </div>
      </VCardText>
      <VDivider />
      <VCardActions class="d-flex justify-center mt-4">
        <VBtn color="primary" variant="tonal" @click="setOptionSelected()">
          Guardar
        </VBtn>
      </VCardActions>
    </VCard>
  </VMenu>
</template>
