<script setup lang="ts">
import initCore from "@core/initCore";
import { initConfigStore, useConfigStore } from "@core/stores/config";
import { hexToRgb } from "@layouts/utils";
import { useTheme } from "vuetify";
import { globalStores } from "./stores/global.stores";

const ScrollToTop = defineAsyncComponent(
  () => import("@core/components/ScrollToTop.vue")
);

const AlertDialog = defineAsyncComponent(
  () => import("@/components/AlertDialog.vue")
);

const Loading = defineAsyncComponent(() => import("@/components/Loading.vue"));

const { global } = useTheme();

// ℹ️ Sync current theme with initial loader theme
initCore();
initConfigStore();

// const userLogin = await authServices.user()
// console.log(userLogin)

const configStore = useConfigStore();
const useGlobalStores = globalStores();
</script>

<template>
  <VLocaleProvider :rtl="configStore.isAppRTL">
    <!-- ℹ️ This is required to set the background color of active nav link based on currently active global theme's primary -->
    <VApp
      :style="`--v-global-theme-primary: ${hexToRgb(
        global.current.value.colors.primary
      )}`"
    >
      <RouterView />

      <ScrollToTop />
      <AlertDialog />
      <Loading />
    </VApp>
  </VLocaleProvider>
</template>
