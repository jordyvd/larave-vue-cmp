<script setup lang="ts">
const List = defineAsyncComponent(
  () => import("@/components/roles/RolesList.vue")
);

import { useRoute } from "vue-router";

const route = useRoute();

const section: string = (route.name as string) || "";
</script>
<template>
  <List :section="section" />
</template>
