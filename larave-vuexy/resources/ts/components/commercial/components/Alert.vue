<script setup lang="ts">
import { onClickOutside } from "@vueuse/core";

const props = defineProps<{
  item: any;
}>();

const emit = defineEmits<{
  (e: "close"): void;
}>();

const alertRef = ref(null);

onClickOutside(alertRef, (event) => emit("close"));

const handleClickOutside = (event: any) => {};
</script>

<template>
  <div class="floating-alert" ref="alertRef">
    <VAlert border="bottom" color="primary">
      <div class="alert-content">
        <small v-if="props.item.APLICACION"
          >APLICACION: {{ props.item.APLICACION }}<br
        /></small>

        <small v-if="props.item.UND">UND: {{ props.item.UND }}</small>

        <small v-if="props.item.PRINCIPAL"
          ><br />PRINCIPAL: {{ props.item.PRINCIPAL }}</small
        >
        <small v-if="props.item.PRINCIPAL"
          ><br />BREÑA: {{ props.item.BREÑA }}</small
        >
        <small v-if="props.item.PRINCIPAL"
          ><br />MERCADERIA: {{ props.item.MERCADERIA }}</small
        >
        <small v-if="props.item.PRINCIPAL"
          ><br />TRANSITO: {{ props.item.TRANSITO }}</small
        >
        <small v-if="props.item.SURCO"
          ><br />SURCO: {{ props.item.SURCO }}</small
        >
        <small
          v-if="
            props.item['LISTA GENERAL'] ||
            props.item['LISTA GERAL 2024'] ||
            props.item['LISTA GENERAL 2023'] ||
            props.item['LISTA GENERAL 2021']
          "
          ><br />LP.GENERAL:
          {{
            props.item["LISTA GENERAL"] ||
            props.item["LISTA GERAL 2024"] ||
            props.item["LISTA GENERAL 2023"] ||
            props.item["LISTA GENERAL 2021"]
          }}</small
        >
      </div>
    </VAlert>
  </div>
</template>
<style lang="scss" scope>
.floating-alert {
  position: fixed; /* Para mantener el alerta fijo en la ventana */
  bottom: 50px; /* Ajusta la posición superior según sea necesario */
  left: 50%; /* Centra horizontalmente */
  transform: translateX(-50%); /* Centra horizontalmente */
  width: 85%;
  z-index: 1000; /* Asegura que esté por encima de otros elementos */
  border-radius: 8px;
}
.alert-content {
  max-height: 20vh;
  overflow-y: auto;

  &::-webkit-scrollbar {
    width: 2px; /* Ancho del scrollbar */
    height: 2px; /* Altura del scrollbar */
  }

  &::-webkit-scrollbar-thumb {
    background-color: white;
  }

  &::-webkit-scrollbar-track {
    background-color: transparent; /* Color de fondo del track (fondo del scrollbar) */
    // border-radius: 2px; /* Borde redondeado del track */
  }

  &::-webkit-scrollbar-corner {
    display: none; /* Ocultar la esquina del scrollbar */
  }
}
</style>
