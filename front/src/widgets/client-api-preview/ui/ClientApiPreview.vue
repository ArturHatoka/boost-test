<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { ElMessage } from 'element-plus'
import { API_BASE_URL } from '@/shared/api/http'
import { useClientList } from '@/features/client-list/model/useClientList'

const {
  clients,
  isLoading,
  isCreating,
  errorMessage,
  filters,
  createForm,
  statusOptions,
  loadClients,
  createClient,
} = useClientList()

const hasClients = computed(() => clients.value.length > 0)

onMounted(async () => {
  await loadClients()
})

async function handleReload() {
  await loadClients()
}

async function handleCreateClient() {
  if (!createForm.name.trim() || !createForm.state.trim()) {
    ElMessage.warning('Name and state are required.')
    return
  }

  await createClient()

  if (!errorMessage.value) {
    ElMessage.success('Client created.')
  }
}
</script>

<template>
  <el-space direction="vertical" :size="16" fill>
    <el-card shadow="never" class="panel">
      <template #header>
        <div class="panel-header">
          <strong>Backend Connection</strong>
        </div>
      </template>

      <el-alert
        type="info"
        :closable="false"
        show-icon
        :title="`Axios base URL: ${API_BASE_URL}`"
      />
    </el-card>

    <el-card shadow="never" class="panel">
      <template #header>
        <div class="panel-header">
          <strong>Filters</strong>
        </div>
      </template>

      <el-form :inline="true" class="filters-row">
        <el-form-item label="State">
          <el-input v-model="filters.state" placeholder="California" clearable />
        </el-form-item>

        <el-form-item label="Status">
          <el-select v-model="filters.status" placeholder="Any" clearable style="width: 160px">
            <el-option
              v-for="status in statusOptions"
              :key="status"
              :label="status"
              :value="status"
            />
          </el-select>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" :loading="isLoading" @click="handleReload">
            Refresh
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <el-card shadow="never" class="panel">
      <template #header>
        <div class="panel-header">
          <strong>Create Client</strong>
        </div>
      </template>

      <el-form :inline="true" class="create-row">
        <el-form-item label="Name">
          <el-input v-model="createForm.name" placeholder="Acme Corp" clearable />
        </el-form-item>

        <el-form-item label="State">
          <el-input v-model="createForm.state" placeholder="California" clearable />
        </el-form-item>

        <el-form-item label="Status">
          <el-select v-model="createForm.status" style="width: 160px">
            <el-option
              v-for="status in statusOptions"
              :key="status"
              :label="status"
              :value="status"
            />
          </el-select>
        </el-form-item>

        <el-form-item>
          <el-button type="success" :loading="isCreating" @click="handleCreateClient">
            Create
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <el-alert
      v-if="errorMessage"
      type="error"
      :closable="false"
      show-icon
      :title="errorMessage"
    />

    <el-card shadow="never" class="panel">
      <template #header>
        <div class="panel-header">
          <strong>Clients</strong>
        </div>
      </template>

      <el-empty v-if="!isLoading && !hasClients" description="No clients found" />

      <el-table v-else :data="clients" stripe v-loading="isLoading">
        <el-table-column prop="id" label="ID" width="80" />
        <el-table-column prop="name" label="Name" min-width="220" />
        <el-table-column prop="state" label="State" min-width="160" />
        <el-table-column label="Status" min-width="120">
          <template #default="{ row }">
            <el-tag :type="row.status === 'Active' ? 'success' : 'info'">
              {{ row.status }}
            </el-tag>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </el-space>
</template>

<style scoped>
.panel {
  border: 1px solid #d9dee8;
}

.panel-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: #1f2a44;
}

.filters-row,
.create-row {
  display: flex;
  flex-wrap: wrap;
}

@media (max-width: 768px) {
  .filters-row,
  .create-row {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
