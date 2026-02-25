export type ClientStatus = 'Active' | 'Inactive'

export interface Client {
  id: number
  name: string
  state: string
  status: ClientStatus
}

export interface ClientListParams {
  limit?: number
  offset?: number
  status?: ClientStatus
  state?: string
}

export interface CreateClientPayload {
  name: string
  state: string
  status?: ClientStatus
}

export interface UpdateClientPayload {
  name: string
  state: string
  status?: ClientStatus
}
