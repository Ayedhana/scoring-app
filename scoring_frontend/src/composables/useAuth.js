import { ref, computed } from 'vue'
import api from '@/services/api'

const isAuthenticated = ref(!!localStorage.getItem('access_token'))
const user = ref(JSON.parse(localStorage.getItem('user') || 'null'))
const loading = ref(false)
const error = ref(null)

export function useAuth() {
  const login = async (email, password) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/v1/login', { email, password })
      const { access_token, user: userData } = response.data
      
      localStorage.setItem('access_token', access_token)
      localStorage.setItem('user', JSON.stringify(userData))
      
      isAuthenticated.value = true
      user.value = userData
      
      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const register = async (name, email, password, password_confirmation) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/v1/register', { name, email, password, password_confirmation })
      const { access_token, user: userData } = response.data

      localStorage.setItem('access_token', access_token)
      localStorage.setItem('user', JSON.stringify(userData))

      isAuthenticated.value = true
      user.value = userData

      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    try {
      await api.post('/v1/logout')
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      localStorage.removeItem('access_token')
      localStorage.removeItem('user')
      isAuthenticated.value = false
      user.value = null
    }
  }

  return {
    login,
    register,
    logout,
    isAuthenticated: computed(() => isAuthenticated.value),
    user: computed(() => user.value),
    loading: computed(() => loading.value),
    error: computed(() => error.value)
  }
}
