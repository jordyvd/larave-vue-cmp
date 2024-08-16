<script setup lang="ts">
import { Store as StoreParams } from "@/services/interfaces/role.interfaces.services";
import roleServices from "@/services/role.services";
import { globalStores } from "@/stores/global.stores";
import { VForm } from "vuetify/lib/components/VForm/index.mjs";

interface Props {
  roleData?: StoreParams;
  isVisible: boolean;
  isEdit: boolean;
}

interface Emit {
  (e: "submit"): void;
  (e: "closeModal"): void;
}

const useGlobalStores = globalStores();

const refForm = ref<VForm | null>(null);

const props = withDefaults(defineProps<Props>(), {
  roleData: () => ({
    id: null,
    name: "",
    description: null,
  }),
});

const emit = defineEmits<Emit>();

const roleData = ref<StoreParams>(structuredClone(toRaw(props.roleData)));

watch(props, () => {
  roleData.value = structuredClone(toRaw(props.roleData));
});

const onFormSubmit = async () => {
  const { valid } = await (refForm.value?.validate() || { valid: false });

  if (!valid) return;

  if (props.isEdit) {
    await updateUser();
  } else {
    await saveRole();
  }
};

const saveRole = async () => {
  useGlobalStores.setShowLoading(true);
  await roleServices.store(roleData.value);
  emit("submit");
};

const updateUser = async () => {
  useGlobalStores.setShowLoading(true);
  await roleServices.update(roleData.value.id as number, roleData.value);
  emit("submit");
};

const onFormReset = () => {
  roleData.value = structuredClone(toRaw(props.roleData));
};

const closeModal = () => {
  emit("closeModal");
};
</script>

<template>
  <VDialog width="500" :model-value="props.isVisible" persistent>
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="closeModal()" />

    <VCard class="pa-sm-10 pa-2">
      <VCardText>
        <!-- 👉 Title -->
        <h4 class="text-h4 text-center mb-2">
          {{ "Roles" }}
        </h4>
        <p class="text-body-1 text-center mb-6">
          {{ isEdit ? "Editar información de rol" : "Nuevo rol de accesso" }}
        </p>

        <!-- 👉 Form -->
        <VForm class="mt-6" @submit.prevent="onFormSubmit" ref="refForm">
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="roleData.name"
                label="Nombre"
                placeholder="Escribir aquí..."
                :rules="[requiredValidator]"
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="roleData.description"
                label="Descripción"
                placeholder="Escribir aquí..."
              />
            </VCol>

            <VCol cols="12" class="d-flex flex-wrap justify-center gap-4">
              <VBtn
                color="secondary"
                variant="tonal"
                @click="onFormReset"
                v-if="!isEdit"
              >
                Limpiar
              </VBtn>

              <VBtn type="submit"> Guardar </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
