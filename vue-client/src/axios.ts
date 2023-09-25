import axios, { type AxiosError } from 'axios'
import type { NullableFields } from '@/types/utils'

export const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  withCredentials: true
})
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error: AxiosError) => {
    return Promise.reject(error)
  }
)

export function useResource<T, R = T, TFilter = Record<string, any>>(pathname: string) {
  const search = (filter: TFilter = {} as TFilter) =>
    api.get<Array<T>>(`/${pathname}`, {
      params: filter
    })
  const show = (id: number) => api.get<T>(`/${pathname}/${id}`)
  const create = (values: R) => api.post(`/${pathname}`, values)
  const update = (id: number, values: NullableFields<Partial<R>>) =>
    api.put<T>(`/${pathname}/${id}`, values)
  const destroy = (id: number) => api.delete<Boolean>(`/${pathname}/${id}`)

  return { search, show, create, update, destroy }
}
