<script lang="ts" setup>
import commercialPricesCommonsServices from "@/services/commercial/prices/commercial.prices.commons.services";
import { sectionStores } from "@/stores/section.stores";
import moment from "moment";

onMounted(() => {
  getLastRefresh();
});

const useSectionStores = sectionStores();

const props = defineProps<{
  params: any;
  actions: number[];
  section: string;
}>();

const emit = defineEmits<{
  (e: "setLoading", value: boolean): void;
  (e: "setReload"): void;
}>();

const timeLeft = ref(0);

const lastRefresh = ref("--/--/-- --:--");

const showConfirm = ref(false);

const exportToExcel = async () => {
  emit("setLoading", true);

  const data = await commercialPricesCommonsServices.exportToExcel(
    props.section,
    props.params
  );

  const url = window.URL.createObjectURL(new Blob([data]));
  const link = document.createElement("a");
  link.href = url;
  link.setAttribute(
    "download",
    `LISTA_PRECIO_${props.section}_${(props.params.search ?? "")
      .replace("%", "_")
      .trim()}.xlsx`
  );
  document.body.appendChild(link);
  link.click();
  emit("setLoading", false);
};

const startTimer = (seconds: number) => {
  timeLeft.value = seconds;
  const intervalId = setInterval(async () => {
    if (timeLeft.value > 0) {
      timeLeft.value--;
    } else {
      emit("setLoading", true);
      clearInterval(intervalId);
      getLastRefresh();
      emit("setReload");
    }
  }, 1000);
};

const refreshData = async () => {
  emit("setLoading", true);
  await commercialPricesCommonsServices.refreshData();
  emit("setLoading", false);
  startTimer(60);
};

const getLastRefresh = async () => {
  emit("setLoading", true);
  const data = await commercialPricesCommonsServices.getLastRefresh();
  lastRefresh.value = moment(data).format("DD/MM/YYYY HH:mm");
  emit("setLoading", false);
};
</script>

<template>
  <ConfirmDialog
    :show="showConfirm"
    v-if="showConfirm"
    :message="'Los datos serán sincronizados con el sistema'"
    @close="showConfirm = false"
    @confirm="
      refreshData();
      showConfirm = false;
    "
  />
  <!-- <alert-dialog
    :show="true"
    title="Error"
    message="Error al sincronizar los datos"
    icon="tabler-alert-triangle"
    color="error"
    @close="showConfirm = false"
    @accept="refreshData"
  /> -->
  <!-- *********** BTN EXPORT *********** -->
  <VBtn
    color="success"
    variant="tonal"
    prepend-icon="tabler-file-type-xls"
    v-if="useSectionStores.getAccessForAction(actions, 7, 'export excel')"
    @click="exportToExcel"
  >
    Exportar
  </VBtn>
  <!-- ********* END BTN EXPORT ********* -->

  <!-- ******** BTN RELOAD ********  -->
  <div v-if="useSectionStores.getAccessForAction(actions, 8, 'btn Actualizar')">
    <VBtn
      color="primary"
      variant="tonal"
      prepend-icon="tabler-database-search"
      @click="showConfirm = true"
      v-if="timeLeft == 0"
    >
      Actualizar
    </VBtn>
    <VBtn color="primary" variant="tonal" v-else disabled>
      <span class="custom-loader mr-2">
        <VIcon icon="tabler-loader" />
      </span>
      {{ timeLeft }}
    </VBtn>
  </div>
  <!-- ******** END BTN RELOAD ******** -->
  <div class="last-refresh">
    <b class="date text-warning">{{ lastRefresh }}</b>
    <small class="spacer">Última actualización</small>
  </div>
</template>
<style lang="scss" scoped>
.last-refresh {
  display: flex;
  flex-direction: column;
}

.last-refresh .date {
  font-size: 0.8rem;
}
</style>
