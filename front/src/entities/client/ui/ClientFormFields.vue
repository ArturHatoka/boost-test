<template>
  <el-form label-position="top">
    <el-form-item label="Name" required>
      <el-input v-model="name" placeholder="Acme Corp" clearable />
    </el-form-item>
    <el-form-item label="State" required>
      <el-input v-model="state" placeholder="California" clearable />
    </el-form-item>
    <el-form-item label="Status">
      <el-select v-model="status" style="width: 100%">
        <el-option
          v-for="item in statusOptions"
          :key="item"
          :label="item"
          :value="item"
        />
      </el-select>
    </el-form-item>
  </el-form>
</template>

<script setup lang="ts">
import type { ClientStatus } from '@/entities/client/model/types'

defineProps<{
  statusOptions: ClientStatus[]
}>()

const name = defineModel<string>('name', { required: true })
const state = defineModel<string>('state', { required: true })
const status = defineModel<ClientStatus>('status', { required: true })
</script>
