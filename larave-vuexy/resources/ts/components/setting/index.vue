<script lang="ts" setup>
const open = ref(["Users", "Admin"]);

const admins = [
  ["Management", "tabler-users"],
  ["Settings", "tabler-settings"],
];

const cruds = [["Create"], ["Read"], ["Update"], ["Delete"]];
</script>

<template>
  <VCard id="invoice-list">
    <VCardText
      class="d-flex justify-space-between align-center flex-wrap gap-4"
    >
    </VCardText>
    <div class="container-all">
      <v-row class="content">
        <v-col cols="3" class="container-menu">
          <VList v-model:opened="open">
            <VListItem prepend-icon="tabler-circle" title="Home" value="Home" />

            <VListGroup value="Users">
              <template #activator="{ props }">
                <VListItem
                  v-bind="props"
                  prepend-icon="tabler-circle"
                  title="Users"
                />
              </template>

              <VListGroup value="Admin">
                <template #activator="{ props }">
                  <VListItem v-bind="props" title="Admin" />
                </template>

                <VListItem
                  v-for="([title], i) in admins"
                  :key="i"
                  :value="title"
                  :title="title"
                  prepend-icon="tabler-circle"
                />
              </VListGroup>

              <VListGroup value="Actions">
                <template #activator="{ props }">
                  <VListItem v-bind="props" title="Actions" />
                </template>

                <VListItem
                  v-for="([title], i) in cruds"
                  :key="i"
                  :value="title"
                  :title="title"
                  prepend-icon="tabler-circle"
                />
              </VListGroup>
            </VListGroup>
          </VList>
        </v-col>
        <v-col cols="9">
          <div class="container-config">
            <v-container>
              <div class="item-value" v-for="item in 20" :key="item">
                L.P. mayorista <VCheckbox />
              </div>
            </v-container>
          </div>
        </v-col>
      </v-row>
    </div>
  </VCard>
</template>
<style lang="scss" scoped>
.container-config {
  width: 100%;
  height: 100%;
}
.container-all {
  height: 100%;
  padding: 0px 20px 20px 20px;
}
.container-all .content {
  height: 100%;
  border: solid 1px rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 5px;
}
.container-menu {
  border-right: solid 1px rgba(var(--v-border-color), var(--v-border-opacity));
}
// .item-values {
//   display: flex;
//   flex-direction: row;
//   border-bottom: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
// }
.item-value {
  display: flex;
  flex-direction: row;
  align-items: center;
  padding: 10px;
}
.invoice-list {
  max-height: 40vh;
}
</style>
