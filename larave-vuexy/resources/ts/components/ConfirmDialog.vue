<script lang="ts" setup>
interface Props {
  show: boolean;
  title: string;
  message: string;
}

const props = withDefaults(defineProps<Props>(), {
  show: false,
  title: "¿Continuar?",
  message: "",
});

const emit = defineEmits<{
  (e: "close"): void;
  (e: "confirm"): void;
}>();
</script>

<template>
  <VDialog v-model="props.show">
    <VCard
      title="Confirmar acción"
      class="mx-auto alert-dialog"
      elevation="16"
      append-icon="$close"
      width="400"
    >
      <template v-slot:append>
        <v-btn
          icon="$close"
          variant="text"
          color="warning"
          @click="emit('close')"
        ></v-btn>
      </template>

      <v-divider></v-divider>

      <div class="py-12 text-center">
        <v-icon
          class="mb-6"
          color="warning"
          icon="tabler-help-hexagon"
          size="128"
        ></v-icon>

        <div class="text-h5 font-weight-bold">{{ props.title }}</div>
        <div class="pr-1 pl-1">
          <small>{{ props.message }}</small>
        </div>
      </div>

      <v-divider></v-divider>

      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn variant="tonal" color="secondary" @click="emit('close')">
          Cancelar
        </VBtn>
        <VBtn @click="emit('confirm')" color="warning"> Confirmar </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>
<style lang="scss">
.alert-dialog .v-card-item {
  padding: 10px 20px 10px 20px !important;
}
</style>
