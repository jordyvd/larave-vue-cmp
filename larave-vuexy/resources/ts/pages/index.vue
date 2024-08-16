<script setup lang="ts">
import wallpaper from '@images/pages/developing.png';
</script>
<template>
  <div>
    <VCard class="mb-6">
      <v-container class="in-construction" :style="{ backgroundImage: `url(${wallpaper})` }">
        <div class="title">sección en desarrollo</div>
      </v-container>
    </VCard>
  </div>
</template>
<style>
.in-construction {
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  width: 100%;
  height: 80vh;
}

.title {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: rgba(var(--v-global-theme-primary), 0.8);
  padding: 20px;
  color: white;
  text-transform: uppercase;
  font-weight: bold;
  align-content: center;
  align-items: center;
  text-align: center;
}
</style>
