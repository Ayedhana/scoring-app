import api from '@/services/api'

export const clientService = {
  // Get all clients with pagination
  getClients(page = 1, perPage = 15) {
    return api.get('/clients', {
      params: {
        page,
        per_page: perPage
      }
    })
  },

  // Get single client details
  getClient(clientId) {
    return api.get(`/clients/${clientId}`)
  },

  // Get client scores history
  getClientScoresHistory(clientId) {
    return api.get(`/clients/${clientId}/scores`)
  },

  // Sync client data from T24
  syncFromT24(clientId) {
    return api.post(`/clients/${clientId}/sync`)
  }
}

export const scoreService = {
  // Get all scores
  getScores(page = 1) {
    return api.get('/scores', {
      params: { page }
    })
  },

  // Get specific score
  getScore(scoreId) {
    return api.get(`/scores/${scoreId}`)
  },

  // Calculate new score
  calculateScore(data) {
    return api.post('/scores/calculate', data)
  }
}
