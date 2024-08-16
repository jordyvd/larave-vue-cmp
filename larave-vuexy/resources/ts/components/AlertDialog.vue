<script lang="ts" setup>
import { globalStores } from "@/stores/global.stores";

const useGlobalStores = globalStores();

const closeDialog = () => {
  useGlobalStores.setShowDialogError(false);
};
</script>

<template>
  <VDialog v-model="useGlobalStores.dialog.show">
    <VCard class="mx-auto w-dialog" elevation="16" width="400">
      <!-- <template v-slot:append>
        <v-btn
          icon="$close"
          :color="useGlobalStores.dialog.color"
          variant="text"
          @click="closeDialog"
        ></v-btn>
      </template> -->

      <!-- <v-divider></v-divider> -->

      <div class="py-12 text-center">
        <v-icon
          class="mb-6"
          :icon="useGlobalStores.dialog.icon"
          :color="useGlobalStores.dialog.color"
          size="128"
        ></v-icon>

        <div class="text-h5 font-weight-bold">
          {{ useGlobalStores.dialog.title }}
        </div>
        <div class="pl-1 pr-1">
          <small>{{ useGlobalStores.dialog.message }}</small>
        </div>
      </div>

      <v-divider></v-divider>

      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn @click="closeDialog" :color="useGlobalStores.dialog.color">
          Aceptar
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>
<style lang="scss">
.w-dialog {
  min-width: 400px;
}
.w-dialog .v-card-item {
  padding: 10px 20px 10px 20px !important;
}
</style>
