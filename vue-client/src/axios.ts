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

export function useResource<T, R = T>(pathname: string) {
  const search = () => api.get<Array<T>>(`/${pathname}`)
  const show = (id: number) => api.get<T>(`/${pathname}/${id}`)
  const update = (id: number, values: NullableFields<Partial<R>>) =>
    api.put<T>(`/${pathname}/${id}`, values)

  return { search, show, update }
}
