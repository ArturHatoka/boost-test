import { http } from '@/shared/api/http'
import type {
  Client,
  ClientListParams,
  CreateClientPayload,
  UpdateClientPayload,
} from '@/entities/client/model/types'

export const clientApi = {
  async list(params: ClientListParams = {}): Promise<Client[]> {
    const { data } = await http.get<Client[]>('/client', { params })
    return data
  },

  async getById(id: number): Promise<Client> {
    const { data } = await http.get<Client>(`/client/${id}`)
    return data
  },

  async create(payload: CreateClientPayload): Promise<Client> {
    const { data } = await http.post<Client>('/client', payload)
    return data
  },

  async update(id: number, payload: UpdateClientPayload): Promise<Client> {
    const { data } = await http.put<Client>(`/client/${id}`, payload)
    return data
  },

  async remove(id: number): Promise<void> {
    await http.delete(`/client/${id}`)
  },
}
