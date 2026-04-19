import api from '@/services/api'

const BASE_URL = '/v1'

export const clientService = {

  // Get all clients
  getClients(page = 1, perPage = 15) {
    return api.get(`${BASE_URL}/clients`, {
      params: {
        page,
        per_page: perPage
      }
    })
  },

  // Get single client
  getClient(clientId) {
    return api.get(`${BASE_URL}/clients/${clientId}`)
  },

  // Get client scores history
  getClientScoresHistory(clientId) {
    return api.get(`${BASE_URL}/clients/${clientId}/scores`)
  },

  // Sync from T24
  syncFromT24(clientId) {
    return api.post(`${BASE_URL}/clients/${clientId}/sync`)
  }
}

export const scoreService = {

  getScores(page = 1) {
    return api.get(`${BASE_URL}/scores`, {
      params: { page }
    })
  },

  getScore(scoreId) {
    return api.get(`${BASE_URL}/scores/${scoreId}`)
  },

  calculateScore(data) {
  return api.post(`${BASE_URL}/scores/calculate`, {
    client_id: String(data.client_id || data)
  })
}
  }
