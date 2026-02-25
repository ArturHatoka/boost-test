import { reactive, ref } from 'vue'
import { clientApi } from '@/entities/client/api/clientApi'
import type {
  Client,
  ClientStatus,
  ClientListParams,
  CreateClientPayload,
} from '@/entities/client/model/types'

type FilterStatus = ClientStatus | ''

const DEFAULT_LIMIT = 100

export function useClientList() {
  const clients = ref<Client[]>([])
  const isLoading = ref(false)
  const isCreating = ref(false)
  const errorMessage = ref('')

  const filters = reactive({
    state: '',
    status: '' as FilterStatus,
  })

  const createForm = reactive({
    name: '',
    state: '',
    status: 'Active' as ClientStatus,
  })

  const statusOptions: ClientStatus[] = ['Active', 'Inactive']

  async function loadClients() {
    isLoading.value = true
    errorMessage.value = ''

    try {
      const params: ClientListParams = {
        limit: DEFAULT_LIMIT,
      }

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

  async function createClient() {
    isCreating.value = true
    errorMessage.value = ''

    try {
      const payload: CreateClientPayload = {
        name: createForm.name.trim(),
        state: createForm.state.trim(),
        status: createForm.status,
      }

      await clientApi.create(payload)
      createForm.name = ''
      createForm.state = ''
      createForm.status = 'Active'
      await loadClients()
    } catch (error) {
      errorMessage.value = extractMessage(error, 'Failed to create client.')
    } finally {
      isCreating.value = false
    }
  }

  return {
    clients,
    isLoading,
    isCreating,
    errorMessage,
    filters,
    createForm,
    statusOptions,
    loadClients,
    createClient,
  }
}

function extractMessage(error: unknown, fallback: string): string {
  if (error instanceof Error && error.message) {
    return error.message
  }

  return fallback
}
