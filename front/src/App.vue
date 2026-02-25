<script setup lang="ts">
import ClientApiPreview from '@/widgets/client-api-preview/ui/ClientApiPreview.vue'
</script>

<template>
  <el-container class="app-shell">
    <el-header class="app-header">
      <div class="app-title-wrap">
        <h1 class="app-title">BoostTest Frontend</h1>
        <p class="app-subtitle">Vue 3 Composition API + axios API layer</p>
      </div>
    </el-header>

    <el-main class="app-main">
      <ClientApiPreview />
    </el-main>
  </el-container>
</template>

<style scoped>
.app-shell {
  min-height: 100vh;
}

.app-header {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #d9dee8;
  background: #f7f9fc;
}

.app-title-wrap {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.app-title {
  margin: 0;
  font-size: 20px;
  font-weight: 700;
  color: #1f2a44;
}

.app-subtitle {
  margin: 0;
  color: #5f6b87;
}

.app-main {
  max-width: 1100px;
  width: 100%;
  margin: 0 auto;
  padding: 24px 20px 40px;
}
</style>
