<script setup lang="ts">
import { Role } from "@/models/role.models";
import { Store as StoreParams } from "@/services/interfaces/user.interfaces.services";
import userServices from "@/services/user.services";
import { globalStores } from "@/stores/global.stores";
import { VForm } from "vuetify/lib/components/VForm/index.mjs";

interface Props {
  userData?: StoreParams;
  isVisible: boolean;
  isEdit: boolean;
}

interface Emit {
  (e: "submit"): void;
  (e: "closeModal"): void;
}

onMounted(() => {
  getRoles();
});

const useGlobalStores = globalStores();

const roles = ref<Role[]>([]);

const refForm = ref<VForm | null>(null);

const props = withDefaults(defineProps<Props>(), {
  userData: () => ({
    id: null,
    name: "",
    email: "",
    password: "",
    role_id: null,
  }),
});

const emit = defineEmits<Emit>();

const getRoles = async () => {
  useGlobalStores.setShowLoading(true);
  const data = await userServices.getRoles();
  roles.value = data;
  useGlobalStores.setShowLoading(false);
};

const userData = ref<StoreParams>(structuredClone(toRaw(props.userData)));

watch(props, () => {
  userData.value = structuredClone(toRaw(props.userData));
});

const onFormSubmit = async () => {
  const { valid } = await (refForm.value?.validate() || { valid: false });

  if (!valid) return;

  if (props.isEdit) {
    await updateUser();
  } else {
    await saveUser();
  }
};

const saveUser = async () => {
  useGlobalStores.setShowLoading(true);
  await userServices.store(userData.value);
  emit("submit");
  //isLoading.value = false;
};

const updateUser = async () => {
  useGlobalStores.setShowLoading(true);
  await userServices.update(userData.value.id as number, userData.value);
  emit("submit");
  //isLoading.value = false;
};

const onFormReset = () => {
  userData.value = structuredClone(toRaw(props.userData));
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
          {{ "Usuario" }}
        </h4>
        <p class="text-body-1 text-center mb-6">
          {{ isEdit ? "Editar información" : "Nuevo acceso al sistema" }}
        </p>

        <!-- 👉 Form -->
        <VForm class="mt-6" @submit.prevent="onFormSubmit" ref="refForm">
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="userData.name"
                label="Nombre"
                placeholder="Escribir aquí..."
                :rules="[requiredValidator]"
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="userData.email"
                label="Correo"
                placeholder="example@example.com"
                :rules="[requiredValidator, emailValidator]"
              />
            </VCol>

            <VCol cols="12">
              <AppSelect
                label="Rol"
                placeholder="Seleccionar una opción..."
                :items="
                  roles.map((item) => {
                    return { title: item.name, value: item.id };
                  })
                "
                v-model="userData.role_id"
                :rules="[requiredValidator]"
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="userData.password"
                type="password"
                label="Contraseña"
                :rules="
                  isEdit && [null, ''].includes(userData.password)
                    ? []
                    : [requiredValidator, passwordValidator]
                "
                placeholder=""
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
