<script setup lang="ts">
const List = defineAsyncComponent(
  () => import("@/components/commercial/prices/infrima/InfrimaList.vue")
);

import { useRoute } from "vue-router";

const route = useRoute();

const section: string = (route.name as string) || "";
</script>

<template>
  <list :section="section" />
</template>
