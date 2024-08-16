<script setup lang="ts">
import Loading from '@/components/Loading.vue'
import authServices from '@/services/auth.services'
import { authStores } from '@/stores/auth.stores'
import { sectionStores } from '@/stores/section.stores'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import authV2LoginIllustrationLight from '@images/pages/principal.png'
import authV2LoginIllustrationDark from '@images/pages/w-principal.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { VForm } from 'vuetify/lib/components/VForm/index.mjs'


definePage({
  meta: {
    layout: 'blank',
  },
})

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const isLoginFailed = ref(false)

const isLoading = ref(false)

const refForm = ref<VForm | null>(null)

const useAuthStores = authStores()
const useSectionStores = sectionStores()

const login = async () => {
  const { valid } = await (refForm.value?.validate() || { valid: false });

  if (!valid) return;

  try {
    isLoading.value = true
    const data = await authServices.login(form.value)
    await localStorage.removeItem('token');
    await localStorage.setItem('token', data.data.token);
    await useAuthStores.getUser();
    await useSectionStores.getSections(useAuthStores.user.id);
    isLoginFailed.value = false
    window.location.href = '/';
    isLoading.value = false
  } catch (error) {
    isLoading.value = false
    isLoginFailed.value = true
    //router.push({ path: "/login" });
  }
}

const isPasswordVisible = ref(false)

const authThemeImg = useGenerateImageVariant(
  authV2LoginIllustrationLight,
  authV2LoginIllustrationDark,
  // authV2LoginIllustrationBorderedLight,
  // authV2LoginIllustrationBorderedDark,
  //true
)

const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)
</script>

<template>
  <Loading v-if="isLoading" :isLoading="isLoading" />
  <RouterLink to="/">
    <div class="auth-logo d-flex align-center gap-x-3">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </RouterLink>

  <VRow no-gutters class="auth-wrapper bg-surface">
    <VCol md="8" class="d-none d-md-flex">
      <div class="position-relative bg-background w-100 me-0">
        <div class="d-flex align-center justify-center w-100 h-100" style="padding-inline: 6.25rem;">
          <VImg max-width="613" :src="authThemeImg" class="auth-illustration mt-16 mb-2" />
        </div>

        <img class="auth-footer-mask" :src="authThemeMask" alt="auth-footer-mask" height="280" width="100">
      </div>
    </VCol>

    <VCol cols="12" md="4" class="auth-card-v2 d-flex align-center justify-center">
      <VCard flat :max-width="500" :min-width="'70%'" class="mt-12 mt-sm-0 pa-4">
        <VCardText>
          <h4 class="text-h4 mb-1">
            ¡Bienvenido!
          </h4>
          <!-- <p class="mb-0">
            Por favor inicie sesión para continuar.
          </p> -->
        </VCardText>
        <VCardText>
          <VAlert :color="isLoginFailed ? 'error' : 'primary'" variant="tonal">
            <p class="text-sm mb-0">
              {{ isLoginFailed ? '¡Crendenciales incorrectas!' : 'Por favor inicie sesión para continuar.' }}
            </p>
          </VAlert>
        </VCardText>
        <VCardText>
          <VForm @submit.prevent="login()" ref="refForm">
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField v-model="form.email" autofocus label="Correo" type="email" placeholder="example@email.com"
                  :rules="[requiredValidator, emailValidator]" />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField v-model="form.password" label="Contraseña" placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  :rules="[requiredValidator, passwordValidator]" />

                <!-- <div class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4">
                  <VCheckbox v-model="form.remember" label="Recordar credenciales" />
                  <a class="text-primary ms-2 mb-1" href="#">
                    Forgot Password?
                  </a>
                </div> -->

                <VBtn block type="submit" class="mt-4">
                  Ingresar
                </VBtn>
              </VCol>

              <!-- create account -->
              <!-- <VCol
                cols="12"
                class="text-center"
              >
                <span>?</span>

                <a
                  class="text-primary ms-2"
                  href="#"
                >
                  Create an account
                </a>
              </VCol> -->
              <!-- <VCol
                cols="12"
                class="d-flex align-center"
              >
                <VDivider />
                <span class="mx-4">or</span>
                <VDivider />
              </VCol> -->

              <!-- auth providers -->
              <!-- <VCol
                cols="12"
                class="text-center"
              >
                <AuthProvider />
              </VCol> -->
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
