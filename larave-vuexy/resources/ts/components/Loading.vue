<script lang="ts" setup>
import { globalStores } from "@/stores/global.stores";

const useGlobalStores = globalStores();
</script>

<template>
  <!-- Dialog -->
  <VDialog v-model="useGlobalStores.showLoading" width="300">
    <VCard color="primary" width="300">
      <VCardText class="pt-3">
        {{ "Cargando..." }}
        <VProgressLinear
          indeterminate
          bg-color="rgba(var(--v-theme-surface), 0.1)"
          :height="8"
          class="mb-0 mt-4"
        />
      </VCardText>
    </VCard>
  </VDialog>
</template>
