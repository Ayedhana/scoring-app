import api from '@/services/api'

const BASE_URL = '/v1'

export const critereService = {

  // Récupérer tous les critères
  getCriteres() {
    return api.get(`${BASE_URL}/criteres`)
  },

  // Récupérer un critère
  getCritere(id) {
    return api.get(`${BASE_URL}/criteres/${id}`)
  },

  // Créer un critère
  createCritere(data) {
    return api.post(`${BASE_URL}/criteres`, data)
  },

  // Modifier un critère
  updateCritere(id, data) {
    return api.put(`${BASE_URL}/criteres/${id}`, data)
  },

  // Mettre à jour toutes les pondérations en une fois
  updateAll(criteres) {
    return api.post(`${BASE_URL}/criteres/update-all`, { criteres })
  },

  // Supprimer un critère
  deleteCritere(id) {
    return api.delete(`${BASE_URL}/criteres/${id}`)
  }
}