import api from '@/services/api'

const BASE_URL = '/v1'

export const userService = {

  getUsers() {
    return api.get(`${BASE_URL}/users`)
  },

  createUser(data) {
    return api.post(`${BASE_URL}/users`, data)
  },

  updateUser(id, data) {
    return api.put(`${BASE_URL}/users/${id}`, data)
  },

  toggleStatus(id) {
    return api.patch(`${BASE_URL}/users/${id}/toggle-status`)
  },

  deleteUser(id) {
    return api.delete(`${BASE_URL}/users/${id}`)
  }
}