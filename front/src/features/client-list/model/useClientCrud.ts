import { reactive, ref } from 'vue'
import { clientApi } from '@/entities/client/api/clientApi'
import type {
  Client,
  ClientListParams,
  CreateClientPayload,
  UpdateClientPayload,
  ClientStatus,
} from '@/entities/client/model/types'

type FilterStatus = ClientStatus | ''

const DEFAULT_LIMIT = 100

export function useClientCrud() {
  const clients = ref<Client[]>([])
  const isLoading = ref(false)
  const isCreating = ref(false)
  const isUpdating = ref(false)
  const deletingClientId = ref<number | null>(null)
  const errorMessage = ref('')

  const filters = reactive({
    state: '',
    status: '' as FilterStatus,
  })

  const statusOptions: ClientStatus[] = ['Active', 'Inactive']

  async function loadClients() {
    isLoading.value = true
    errorMessage.value = ''

    try {
      const params: ClientListParams = { limit: DEFAULT_LIMIT }

      if (filters.state.trim() !== '') {
        params.state = filters.state.trim()
      }
      if (filters.status !== '') {
        params.status = filters.status
      }

      clients.value = await clientApi.list(params)
    } catch (error) {
      errorMessage.value = extractMessage(error, 'Failed to load clients.')
    } finally {
      isLoading.value = false
    }
  }

  async function createClient(payload: CreateClientPayload): Promise<boolean> {
    isCreating.value = true
    errorMessage.value = ''

    try {
      await clientApi.create(payload)
      await loadClients()
      return true
    } catch (error) {
      errorMessage.value = extractMessage(error, 'Failed to create client.')
      return false
    } finally {
      isCreating.value = false
    }
  }

  async function updateClient(id: number, payload: UpdateClientPayload): Promise<boolean> {
    isUpdating.value = true
    errorMessage.value = ''

    try {
      await clientApi.update(id, payload)
      await loadClients()
      return true
    } catch (error) {
      errorMessage.value = extractMessage(error, 'Failed to update client.')
      return false
    } finally {
      isUpdating.value = false
    }
  }

  async function removeClient(id: number): Promise<boolean> {
    deletingClientId.value = id
    errorMessage.value = ''

    try {
      await clientApi.remove(id)
      await loadClients()
      return true
    } catch (error) {
      errorMessage.value = extractMessage(error, 'Failed to delete client.')
      return false
    } finally {
      deletingClientId.value = null
    }
  }

  function setValidationError(message: string) {
    errorMessage.value = message
  }

  function clearError() {
    errorMessage.value = ''
  }

  return {
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
  }
}

function extractMessage(error: unknown, fallback: string): string {
  if (error instanceof Error && error.message) {
    return error.message
  }

  return fallback
}
