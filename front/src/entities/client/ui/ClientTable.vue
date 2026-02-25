<template>
  <el-table :data="clients" stripe v-loading="isLoading">
    <el-table-column prop="id" label="ID" width="80" />
    <el-table-column prop="name" label="Name" min-width="220" />
    <el-table-column prop="state" label="State" min-width="160" />
    <el-table-column label="Status" min-width="210">
      <template #default="{ row }">
        <el-switch
          :model-value="row.status"
          :active-value="'Active'"
          :inactive-value="'Inactive'"
          active-text="Active"
          inactive-text="Inactive"
          inline-prompt
          :loading="Boolean(statusUpdatingById[row.id])"
          @change="onStatusChange(row, $event)"
        />
      </template>
    </el-table-column>
    <el-table-column label="Actions" min-width="200" align="right">
      <template #default="{ row }">
        <el-space>
          <el-button size="small" @click="onEdit(row)">Edit</el-button>
          <el-button
            size="small"
            type="danger"
            :loading="deletingClientId === row.id"
            @click="onDelete(row)"
          >
            Delete
          </el-button>
        </el-space>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup lang="ts">
import type { Client, ClientStatus } from '@/entities/client/model/types'

defineProps<{
  clients: Client[]
  isLoading: boolean
  deletingClientId: number | null
  statusUpdatingById: Record<number, boolean>
}>()

const emit = defineEmits<{
  edit: [client: Client]
  delete: [client: Client]
  statusChange: [payload: { client: Client; status: ClientStatus }]
}>()

function onEdit(client: Client) {
  emit('edit', client)
}

function onDelete(client: Client) {
  emit('delete', client)
}

function onStatusChange(client: Client, status: unknown) {
  if (status !== 'Active' && status !== 'Inactive') {
    return
  }

  emit('statusChange', { client, status })
}
</script>
