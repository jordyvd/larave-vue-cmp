import type { App } from 'vue'

import AlertDialog from '@/components/AlertDialog.vue'
import ConfirmDialog from '@/components/ConfirmDialog.vue'
import Loading from "@/components/Loading.vue"

export const registerGlobalComponents = (app: App) => {
  app.component('Loading', Loading)
  app.component('ConfirmDialog', ConfirmDialog)
  app.component('AlertDialog', AlertDialog)
}
