<template>
  <AppLayout>
    <div class="page-top">
      <div>
        <h1 class="page-title">Historique des Scores</h1>
        <p class="page-sub">{{ historique.length }} analyse(s)</p>
      </div>
    </div>

    <!-- Chargement -->
    <div v-if="loading" class="loading">Chargement...</div>

    <!-- Erreur -->
    <div v-else-if="error" class="error-msg">{{ error }}</div>

    <!-- TABLE -->
    <div v-else class="table-card">
      <table class="clients-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nom Client</th>
            <th>Score</th>
            <th>Niveau de Risque</th>
            <th>Décision</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(item, index) in historique"
            :key="item.id"
            class="client-row"
          >
            <td class="col-num">{{ index + 1 }}</td>
            <td class="col-name">
              {{ item.client?.nom }} {{ item.client?.prenom }}
            </td>
            <td>
              <strong class="score-val">{{ item.score_total }}</strong>
              <span class="dt">/1200</span>
            </td>
            <td>
              <span :class="['status-badge', getRisqueClass(item.niveau_risque)]">
                <span class="status-dot"></span>
                {{ formatRisque(item.niveau_risque) }}
              </span>
            </td>
            <td>
              <span :class="['status-badge', getDecisionClass(item.score_total)]">
                <span class="status-dot"></span>
                {{ getDecisionLabel(item.score_total) }}
              </span>
            </td>
            <td class="col-date">
              {{ formatDate(item.calculated_at || item.created_at) }}
            </td>
          </tr>

          <!-- Vide -->
          <tr v-if="historique.length === 0">
            <td colspan="6" class="empty-row">
              <div class="empty-state">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5">
                  <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <p>Aucun score calculé pour le moment</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '@/components/AppLayout.vue'
import { scoreService } from '@/services/clientService'

const historique = ref([])
const loading = ref(false)
const error = ref('')

onMounted(async () => {
  loading.value = true
  try {
    const res = await scoreService.getScores()
    const data = res.data.data || res.data
    
    // ✅ Garder seulement le dernier score par client
    const seen = new Set()
    historique.value = data.filter(item => {
      const clientId = item.client?.id || item.client_id
      if (seen.has(clientId)) return false
      seen.add(clientId)
      return true
    })
  } catch (err) {
    error.value = 'Erreur lors du chargement de l\'historique'
    console.error(err)
  } finally {
    loading.value = false
  }
})

// ✅ Formatage du niveau de risque
function formatRisque(niveau) {
  if (niveau === 'faible') return 'Faible'
  if (niveau === 'moyen') return 'Moyen'
  return 'Élevé'
}

// ✅ Classe CSS du niveau de risque
function getRisqueClass(niveau) {
  if (niveau === 'faible') return 'status-active'
  if (niveau === 'moyen') return 'status-warning'
  return 'status-inactive'
}

// ✅ Classe CSS de la décision
function getDecisionClass(score) {
  const s = parseFloat(score)
  if (s >= 900) return 'status-active'
  if (s >= 600) return 'status-warning'
  return 'status-inactive'
}

// ✅ Label de la décision
function getDecisionLabel(score) {
  const s = parseFloat(score)
  if (s >= 900) return 'Accepté'
  if (s >= 600) return 'Moyen'
  return 'Refusé'
}

// ✅ Formatage de la date
function formatDate(dateStr) {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('fr-TN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit'
  })
}
</script>

<style scoped>
.page-top { margin-bottom: 20px; }

.page-title {
  font-size: 22px;
  font-weight: 700;
  color: #1a2744;
  margin: 0 0 3px;
}

.page-sub {
  font-size: 13px;
  color: #94a3b8;
  margin: 0;
}

.table-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e0f2f1;
  box-shadow: 0 1px 4px rgba(15, 79, 79, 0.06);
}

.clients-table {
  width: 100%;
  border-collapse: collapse;
}

.clients-table thead tr {
  background: #0f4f4f;
}

.clients-table thead th {
  padding: 13px 16px;
  text-align: left;
  font-size: 11.5px;
  font-weight: 600;
  color: #99cece;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  border: none;
}

.clients-table tbody td {
  padding: 13px 16px;
  font-size: 13.5px;
  color: #374151;
  border-bottom: 1px solid #f1f5f9;
}

.clients-table tbody tr:last-child td { border-bottom: none; }

.client-row { transition: background 0.12s; }
.client-row:hover { background: #f0faf9; }

.col-num { color: #94a3b8; font-size: 12px; width: 40px; }
.col-name { font-weight: 500; color: #1a2744; }
.col-date { color: #64748b; font-size: 13px; }

.score-val {
  color: #0f4f4f;
  font-size: 15px;
  font-weight: 700;
}

.dt { color: #94a3b8; font-size: 12px; }

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-active  { background: #dcfce7; color: #15803d; }
.status-warning { background: #fef3c7; color: #b45309; }
.status-inactive { background: #fee2e2; color: #b91c1c; }

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: currentColor;
}

.loading {
  text-align: center;
  padding: 40px;
  color: #94a3b8;
}

.error-msg {
  background: #fee2e2;
  color: #b91c1c;
  padding: 12px;
  border-radius: 8px;
}

.empty-row { padding: 0 !important; }

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 48px 20px;
  gap: 10px;
  color: #94a3b8;
  font-size: 14px;
}

.empty-state p { margin: 0; }
</style>