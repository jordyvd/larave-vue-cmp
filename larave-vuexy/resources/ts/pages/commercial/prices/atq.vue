<script setup lang="ts">
import { useRoute } from "vue-router";

const List = defineAsyncComponent(
  () => import("@/components/commercial/prices/atq/AtqList.vue")
);

const route = useRoute();

const section: string = (route.name as string) || "";
</script>

<template>
  <List :section="section" />
</template>
