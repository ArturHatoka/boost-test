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
        <div class="panel-header panel-header-actions">
          <strong>Clients</strong>
          <el-button type="primary" @click="handleOpenCreateDialog">Create client</el-button>
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
          <el-button type="primary" :loading="isLoading" @click="handleReload">Refresh</el-button>
        </el-form-item>
      </el-form>

      <el-alert
        v-if="errorMessage"
        class="error-alert"
        type="error"
        :closable="false"
        show-icon
        :title="errorMessage"
      />

      <el-empty v-if="!isLoading && !hasClients" description="No clients found" />

      <ClientTable
        v-else
        :clients="clients"
        :is-loading="isLoading"
        :deleting-client-id="deletingClientId"
        @edit="handleOpenEditDialog"
        @delete="handleDeleteClient"
      />
    </el-card>
  </el-space>

  <el-dialog
    v-model="createDialogVisible"
    title="Create client"
    width="520px"
    :close-on-click-modal="!isCreating"
  >
    <ClientFormFields
      v-model:name="createForm.name"
      v-model:state="createForm.state"
      v-model:status="createForm.status"
      :status-options="statusOptions"
    />

    <template #footer>
      <el-button :disabled="isCreating" @click="closeCreateDialog">Cancel</el-button>
      <el-button type="primary" :loading="isCreating" @click="handleCreateClient">Create</el-button>
    </template>
  </el-dialog>

  <el-dialog
    v-model="editDialogVisible"
    title="Edit client"
    width="520px"
    :close-on-click-modal="!isUpdating"
  >
    <ClientFormFields
      v-model:name="editForm.name"
      v-model:state="editForm.state"
      v-model:status="editForm.status"
      :status-options="statusOptions"
    />

    <template #footer>
      <el-button :disabled="isUpdating" @click="closeEditDialog">Cancel</el-button>
      <el-button type="primary" :loading="isUpdating" @click="handleUpdateClient">Save</el-button>
    </template>
  </el-dialog>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import type { Client, CreateClientPayload, UpdateClientPayload } from '@/entities/client/model/types'
import { API_BASE_URL } from '@/shared/api/http'
import { useClientCrud } from '@/features/client-list/model/useClientCrud'
import { isValidClientForm, useClientDialogs } from '@/features/client-list/model/useClientDialogs'
import ClientFormFields from '@/entities/client/ui/ClientFormFields.vue'
import ClientTable from '@/entities/client/ui/ClientTable.vue'

const {
  clients,
  isLoading,
  isCreating,
  isUpdating,
  deletingClientId,
  errorMessage,
  filters,
  statusOptions,
  loadClients,
  createClient,
  updateClient,
  removeClient,
  setValidationError,
  clearError,
} = useClientCrud()

const {
  createDialogVisible,
  editDialogVisible,
  editingClientId,
  createForm,
  editForm,
  openCreateDialog,
  closeCreateDialog,
  openEditDialog,
  closeEditDialog,
} = useClientDialogs()

const hasClients = computed(() => clients.value.length > 0)

onMounted(async () => {
  await loadClients()
})

async function handleReload() {
  await loadClients()
}

function handleOpenCreateDialog() {
  clearError()
  openCreateDialog()
}

function handleOpenEditDialog(client: Client) {
  clearError()
  openEditDialog(client)
}

async function handleCreateClient() {
  if (!isValidClientForm(createForm)) {
    setValidationError('Name and state are required.')
    return
  }

  const payload: CreateClientPayload = {
    name: createForm.name.trim(),
    state: createForm.state.trim(),
    status: createForm.status,
  }

  const success = await createClient(payload)
  if (success) {
    closeCreateDialog()
    ElMessage.success('Client created.')
  }
}

async function handleUpdateClient() {
  if (editingClientId.value === null) {
    setValidationError('Select a client to update.')
    return
  }

  if (!isValidClientForm(editForm)) {
    setValidationError('Name and state are required.')
    return
  }

  const payload: UpdateClientPayload = {
    name: editForm.name.trim(),
    state: editForm.state.trim(),
    status: editForm.status,
  }

  const success = await updateClient(editingClientId.value, payload)
  if (success) {
    closeEditDialog()
    ElMessage.success('Client updated.')
  }
}

async function handleDeleteClient(client: Client) {
  try {
    await ElMessageBox.confirm(`Delete client "${client.name}"?`, 'Confirm delete', {
      type: 'warning',
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel',
      autofocus: false,
    })
  } catch (error) {
    if (isDialogCancel(error)) {
      return
    }
    ElMessage.error('Delete confirmation failed.')
    return
  }

  const success = await removeClient(client.id)
  if (success) {
    ElMessage.success('Client deleted.')
  }
}

function isDialogCancel(error: unknown): boolean {
  return error === 'cancel' || error === 'close'
}
</script>

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

.panel-header-actions {
  gap: 12px;
}

.filters-row {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 8px;
}

.error-alert {
  margin: 12px 0;
}

@media (max-width: 768px) {
  .filters-row {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
