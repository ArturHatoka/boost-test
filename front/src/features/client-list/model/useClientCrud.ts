import { reactive, ref, type Ref } from 'vue'
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
  const statusUpdatingById = reactive<Record<number, boolean>>({})
  const errorMessage = ref('')

  const filters = reactive({
    state: '',
    status: '' as FilterStatus,
  })

  const statusOptions: ClientStatus[] = ['Active', 'Inactive']

  async function loadClients() {
    await runWithLoading(isLoading, 'Failed to load clients.', async () => {
      clients.value = await clientApi.list(buildListParams(filters))
    })
  }

  async function createClient(payload: CreateClientPayload): Promise<boolean> {
    return runWithLoading(isCreating, 'Failed to create client.', async () => {
      await clientApi.create(payload)
      await loadClients()
    })
  }

  async function updateClient(id: number, payload: UpdateClientPayload): Promise<boolean> {
    return runWithLoading(isUpdating, 'Failed to update client.', async () => {
      await clientApi.update(id, payload)
      await loadClients()
    })
  }

  async function removeClient(id: number): Promise<boolean> {
    deletingClientId.value = id

    return runAction(
      'Failed to delete client.',
      async () => {
      await clientApi.remove(id)
      await loadClients()
      },
      () => {
      deletingClientId.value = null
      },
    )
  }

  async function changeClientStatus(client: Client, status: ClientStatus): Promise<boolean> {
    if (client.status === status) {
      return true
    }

    statusUpdatingById[client.id] = true

    return runAction(
      'Failed to update client status.',
      async () => {
        const updated = await clientApi.update(client.id, {
          name: client.name,
          state: client.state,
          status,
        })

        applyClientUpdate(updated)
      },
      () => {
        delete statusUpdatingById[client.id]
      },
    )
  }

  function applyClientUpdate(updated: Client): void {
    const index = clients.value.findIndex((item) => item.id === updated.id)
    if (index === -1) {
      return
    }

    clients.value[index] = updated

    if (filters.status !== '' && updated.status !== filters.status) {
      clients.value = clients.value.filter((item) => item.id !== updated.id)
    }
  }

  function setValidationError(message: string) {
    errorMessage.value = message
  }

  function clearError() {
    errorMessage.value = ''
  }

  async function runWithLoading(
    loading: Ref<boolean>,
    fallback: string,
    action: () => Promise<void>,
  ): Promise<boolean> {
    loading.value = true

    return runAction(
      fallback,
      action,
      () => {
        loading.value = false
      },
    )
  }

  async function runAction(
    fallback: string,
    action: () => Promise<void>,
    onFinally?: () => void,
  ): Promise<boolean> {
    errorMessage.value = ''

    try {
      await action()
      return true
    } catch (error) {
      errorMessage.value = extractMessage(error, fallback)
      return false
    } finally {
      onFinally?.()
    }
  }

  return {
    clients,
    isLoading,
    isCreating,
    isUpdating,
    deletingClientId,
    statusUpdatingById,
    errorMessage,
    filters,
    statusOptions,
    loadClients,
    createClient,
    updateClient,
    removeClient,
    changeClientStatus,
    setValidationError,
    clearError,
  }
}

function buildListParams(filters: { state: string; status: FilterStatus }): ClientListParams {
  const params: ClientListParams = { limit: DEFAULT_LIMIT }

  if (filters.state.trim() !== '') {
    params.state = filters.state.trim()
  }
  if (filters.status !== '') {
    params.status = filters.status
  }

  return params
}

function extractMessage(error: unknown, fallback: string): string {
  if (error instanceof Error && error.message) {
    return error.message
  }

  return fallback
}
