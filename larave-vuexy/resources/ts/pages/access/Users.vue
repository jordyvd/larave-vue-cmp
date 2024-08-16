<script setup lang="ts">
const List = defineAsyncComponent(
  () => import("@/components/users/UsersList.vue")
);

import { useRoute } from "vue-router";

const route = useRoute();

const section: string = (route.name as string) || "";
</script>

<template>
  <List :section="section" />
</template>
<style lang="scss">
#invoice-list {
  .invoice-list-actions {
    inline-size: 8rem;
  }

  .invoice-list-filter {
    inline-size: 12rem;
  }
}
</style>
