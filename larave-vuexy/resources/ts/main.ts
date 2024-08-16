import { createApp } from 'vue'

import App from '@/App.vue'
import { authStores } from '@/stores/auth.stores'
import { sectionStores } from '@/stores/section.stores'
import { registerGlobalComponents } from '@core/utils/componentsGlobal'
import { registerPlugins } from '@core/utils/plugins'

// Styles
import '@core-scss/template/index.scss'
import '@styles/styles.scss'
import { router } from './plugins/1.router'
// Create vue app
const app = createApp(App)

registerPlugins(app)

registerGlobalComponents(app)

const useSectionStores = sectionStores()

const useAuthStores = authStores()

async function userInfo() {
  try {
    await useAuthStores.getUser()
    await useSectionStores.getSections(useAuthStores.user.role.id)
    const currentRoute = router.currentRoute.value.path
    const isLogin = currentRoute === "/login"
    if (isLogin) {
      router.push({ path: "/" });
    }
    // Mount vue app
    app.mount('#app')
  } catch (e) {
    router.push({ path: "/login" });
    console.log(e)
    app.mount('#app')
  }
}

userInfo();


