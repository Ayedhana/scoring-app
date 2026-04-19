<template>
 <AppLayout>
  <div class="rapports-page">
    <h1>Rapports</h1>
    <p class="subtitle">Statistiques et rapports des scores clients</p>

    <div class="stats-grid">
      <div class="stat-card">
        <h3>Score Moyen</h3>
        <p class="stat-value">{{ stats.score_moyen }}</p>
      </div>
      <div class="stat-card">
        <h3>Dossiers Acceptés</h3>
        <p class="stat-value accepted">{{ stats.acceptes }}</p>
      </div>
      <div class="stat-card">
        <h3>Dossiers Refusés</h3>
        <p class="stat-value refused">{{ stats.refuses }}</p>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/components/AppLayout.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'

const stats = ref({
  score_moyen: 0,
  acceptes: 0,
  refuses: 0
})

const fetchStats = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/rapports/stats', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })

    stats.value = response.data

  } catch (error) {
    console.error("Erreur API:", error)
  }
}

onMounted(() => {
  fetchStats()
})
</script>

<style scoped>
.rapports-page { padding: 2rem; }
.subtitle { color: #666; margin-bottom: 2rem; }
.stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
.stat-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
.stat-card h3 { color: #333; margin-bottom: 0.5rem; }
.stat-value { font-size: 2rem; font-weight: bold; color: #1a5c4f; }
.accepted { color: #28a745; }
.refused { color: #dc3545; }
</style>