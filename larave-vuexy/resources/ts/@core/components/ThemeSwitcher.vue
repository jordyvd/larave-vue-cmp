<script setup lang="ts">
import { useConfigStore } from '@core/stores/config';
import type { ThemeSwitcherTheme } from '@layouts/types';

const props = defineProps<{
  themes: ThemeSwitcherTheme[]
}>()

const configStore = useConfigStore()

const selectedItem = ref([configStore.theme])

const switchTheme = ref(false)

switchTheme.value = configStore.theme === 'light'

// Update icon if theme is changed from other sources
watch(
  () => configStore.theme,
  () => {
    selectedItem.value = [configStore.theme]
  },
  { deep: true },
)

const toggleSwitch = () => {
  switchTheme.value = !switchTheme.value
  console.log(switchTheme.value)
  configStore.theme = switchTheme.value ? 'light' : 'dark';
}
</script>

<template>
  <div class="switch-container" @click="toggleSwitch">
    <div :class="{ 'switch': true, 'switch-active': switchTheme }">
      <div :class="{ 'switch-handle': true, 'switch-handle-active': switchTheme }">
        <v-icon v-if="switchTheme" icon="tabler-sun-filled" />
        <v-icon v-else icon="tabler-moon-filled" />
      </div>
    </div>
  </div>
</template>
<style scoped>
.switch-container {
  display: inline-block;
  cursor: pointer;
}

.switch {
  width: 50px;
  height: 25px;
  border-radius: 12.5px;
  background: #25293C;
  position: relative;
  transition: background-color 0.3s;
}

.switch-active {
  background-color: rgba(var(--v-theme-on-surface), 0.1);
}

.switch-handle {
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: rgba(var(--v-theme-on-surface), 0.1);
  position: absolute;
  top: 0;
  left: 0;
  transition: left 0.3s;
  color: rgba(var(--v-global-theme-primary), var(--v-medium-emphasis-opacity));
  display: flex;
  align-items: center;
  justify-content: center;
}

.switch-handle-active {
  left: 25px;
  background-color: rgb(var(--v-global-theme-primary));
  color: white;
  /* Mueve el handle hacia la derecha cuando está activo */
}

.switch-icon {
  font-size: 18px;
  color: #4caf50;
  /* Color del icono */
}
</style>
