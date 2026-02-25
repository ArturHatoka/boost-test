import { reactive, ref } from 'vue'
import type { Client, ClientFormModel, ClientStatus } from '@/entities/client/model/types'

export function useClientDialogs() {
  const createDialogVisible = ref(false)
  const editDialogVisible = ref(false)
  const editingClientId = ref<number | null>(null)

  const createForm = reactive<ClientFormModel>(emptyForm())
  const editForm = reactive<ClientFormModel>(emptyForm())

  function openCreateDialog() {
    resetForm(createForm)
    createDialogVisible.value = true
  }

  function closeCreateDialog() {
    createDialogVisible.value = false
  }

  function openEditDialog(client: Client) {
    editingClientId.value = client.id
    editForm.name = client.name
    editForm.state = client.state
    editForm.status = client.status
    editDialogVisible.value = true
  }

  function closeEditDialog() {
    editDialogVisible.value = false
    editingClientId.value = null
  }

  return {
    createDialogVisible,
    editDialogVisible,
    editingClientId,
    createForm,
    editForm,
    openCreateDialog,
    closeCreateDialog,
    openEditDialog,
    closeEditDialog,
  }
}

export function isValidClientForm(form: ClientFormModel): boolean {
  return form.name.trim() !== '' && form.state.trim() !== ''
}

function emptyForm(): ClientFormModel {
  return {
    name: '',
    state: '',
    status: 'Active' as ClientStatus,
  }
}

function resetForm(form: ClientFormModel): void {
  form.name = ''
  form.state = ''
  form.status = 'Active'
}
