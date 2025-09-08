import axios from 'axios'
import router from '@/router/index'

export const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:3000',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('access_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  else delete config.headers.Authorization
  return config
})

let isLoggingOut = false
function logoutAndRedirect() {
  if (isLoggingOut) return
  isLoggingOut = true
  const toLogin = { name: 'login' }
  localStorage.removeItem('access_token')
  localStorage.removeItem('user')
  if (router && router.currentRoute && router.currentRoute.value.name !== 'login') {
    router.push(toLogin).finally(() => (isLoggingOut = false))
  } else {
    window.location.href = '/login'
  }
}

api.interceptors.response.use(
  (res) => res,
  (error) => {
    const status = error?.response?.status
    if (status === 401) {
      logoutAndRedirect()
    }
    return Promise.reject(error)
  },
)
