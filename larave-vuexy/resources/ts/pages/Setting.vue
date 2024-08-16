<script setup lang="ts">
import { useRoute } from "vue-router";

const Config = defineAsyncComponent(
  () => import("@/components/setting/index.vue")
);

const route = useRoute();

const section: string = (route.name as string) || "";
</script>
<template>
  <Config :section="section" />
</template>
