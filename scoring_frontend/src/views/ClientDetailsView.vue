<template>
  <AppLayout>
    <div class="page-top">
      <h1 class="page-title">Analyse Client</h1>
      <p class="page-sub">Scoring & évaluation du risque</p>
    </div>

    <!-- Chargement -->
    <div v-if="loading" class="loading">
      Chargement...
    </div>

    <!-- Erreur -->
    <div v-else-if="error" class="error-msg">
      {{ error }}
    </div>

    <!-- Contenu -->
    <div v-else-if="client" class="grid">

      <!-- CLIENT INFO -->
      <div class="card">
        <div class="avatar">
          {{ (client.nom || '?').charAt(0) }}
        </div>
        <h3 class="client-name">{{ client.nom }} {{ client.prenom }}</h3>
        <p class="email">{{ client.email }}</p>
        <div class="divider"></div>
        <div class="info">
          <div class="info-row">
            <span class="info-label">Revenu mensuel</span>
            <span class="info-value">{{ (client.balance || 0).toLocaleString('fr-TN') }} DT</span>
          </div>
          <div class="info-row">
            <span class="info-label">Solde moyen</span>
            <span class="info-value">{{ (client.solde_moyen || 0).toLocaleString('fr-TN') }} DT</span>
          </div>
          <div class="info-row">
            <span class="info-label">Ancienneté</span>
            <span class="info-value">{{ client.anciennete_mois }} mois</span>
          </div>
          <div class="info-row">
            <span class="info-label">Incidents</span>
            <span class="info-value">{{ client.nombre_incidents }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Crédits</span>
            <span class="info-value">{{ (client.montant_credits || 0).toLocaleString('fr-TN') }} DT</span>
          </div>
          <div class="info-row">
            <span class="info-label">Statut</span>
            <span :class="client.status === 'active' ? 'badge green' : 'badge gray'">
              {{ client.status === 'active' ? 'Actif' : 'Inactif' }}
            </span>
          </div>
        </div>
      </div>

      <!-- SCORE CARD -->
      <div class="card">
        <h3 class="section-title">Score Crédit</h3>

        <div class="score" :class="scoreColor">
          {{ scoreTotal }}
          <span class="score-max">/1200</span>
        </div>

        <div class="progress">
          <div class="progress-bar" :style="{ width: (scoreTotal / 1200 * 100) + '%' }"></div>
        </div>

        <div class="risk">
          <span>Niveau de risque :</span>
          <span :class="['badge', riskClass]">{{ riskLabel }}</span>
        </div>

        <div class="details">
          <h4>Détail du scoring</h4>
          <div class="detail-row">
            <span>📅 Ancienneté du compte</span>
            <span class="detail-val">{{ detailScores.anciennete }} pts</span>
          </div>
          <div class="detail-row">
            <span>⚠️ Incidents de paiement</span>
            <span class="detail-val">{{ detailScores.incidents }} pts</span>
          </div>
          <div class="detail-row">
            <span>💳 Montant des crédits</span>
            <span class="detail-val">{{ detailScores.credits }} pts</span>
          </div>
          <div class="detail-row">
            <span>💰 Solde moyen</span>
            <span class="detail-val">{{ detailScores.solde }} pts</span>
          </div>
        </div>

        <textarea
          v-model="report"
          class="report"
          placeholder="Ajouter une analyse ou un commentaire..."
          rows="3"
        ></textarea>

        <div class="actions">
          <button class="btn primary" @click="calculateAndSave">
            Calculer & Sauvegarder
          </button>
          <button class="btn outline" @click="goBack">Retour</button>
        </div>

        <div v-if="successMsg" class="success-msg">{{ successMsg }}</div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import AppLayout from "@/components/AppLayout.vue"
import { clientService, scoreService } from "@/services/clientService"

const route = useRoute()
const router = useRouter()

const client = ref(null)
const loading = ref(false)
const error = ref('')
const report = ref('')
const successMsg = ref('')
const scoreTotal = ref(0)
const detailScores = ref({
  anciennete: 0,
  incidents: 0,
  credits: 0,
  solde: 0
})

// ✅ Charger le client depuis l'API
onMounted(async () => {
  loading.value = true
  try {
    const id = route.params.id
    const res = await clientService.getClient(id)
    client.value = res.data
    calculateScore()
  } catch (err) {
    error.value = 'Client introuvable'
    console.error(err)
  } finally {
    loading.value = false
  }
})

// ✅ Calcul local du score
function calculateScore() {
  if (!client.value) return

  const c = client.value

  const anciennete =
    c.anciennete_mois >= 60 ? 300 :
    c.anciennete_mois >= 36 ? 220 :
    c.anciennete_mois >= 12 ? 150 : 70

  const incidents =
    c.nombre_incidents === 0 ? 300 :
    c.nombre_incidents <= 2 ? 180 :
    c.nombre_incidents <= 5 ? 90 : 30

  const credits =
    c.montant_credits <= 10000 ? 300 :
    c.montant_credits <= 50000 ? 200 :
    c.montant_credits <= 100000 ? 120 : 50

  const solde =
    c.solde_moyen >= 50000 ? 300 :
    c.solde_moyen >= 20000 ? 210 :
    c.solde_moyen >= 5000 ? 130 : 60

  detailScores.value = {
    anciennete,
    incidents,
    credits,
    solde
  }

  scoreTotal.value = anciennete + incidents + credits + solde
}

// ✅ Sauvegarder le score via API
async function calculateAndSave() {
  try {
    const res = await scoreService.calculateScore({
      client_id: route.params.id
    })

    if (res.data.score) {
      scoreTotal.value = Number(res.data.score.score_total)
    }

    successMsg.value = '✅ Score sauvegardé avec succès !'
    setTimeout(() => successMsg.value = '', 3000)

  } catch (err) {
    successMsg.value = '❌ Erreur : ' + (err.response?.data?.message || err.message)
    console.error(err)
  }
}
// ✅ Computed
const riskLabel = computed(() => {
  if (scoreTotal.value >= 900) return 'Faible'
  if (scoreTotal.value >= 600) return 'Moyen'
  return 'Élevé'
})

const riskClass = computed(() => {
  if (scoreTotal.value >= 900) return 'green'
  if (scoreTotal.value >= 600) return 'orange'
  return 'red'
})

const scoreColor = computed(() => {
  if (scoreTotal.value >= 900) return 'score-green'
  if (scoreTotal.value >= 600) return 'score-orange'
  return 'score-red'
})

function goBack() {
  router.push('/')
}
</script>

<style scoped>
.page-title { font-size: 22px; font-weight: 700; color: #1a2744; }
.page-sub { color: #94a3b8; margin-bottom: 24px; }

.grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 20px;
}

.card {
  background: white;
  padding: 24px;
  border-radius: 12px;
  border: 1px solid #e0f2f1;
  box-shadow: 0 1px 4px rgba(15,79,79,0.06);
}

.avatar {
  width: 64px;
  height: 64px;
  background: #0f4f4f;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 12px;
  font-size: 24px;
  font-weight: bold;
}

.client-name { text-align: center; font-size: 18px; color: #1a2744; margin: 0; }
.email { text-align: center; color: #94a3b8; font-size: 13px; margin: 4px 0; }
.divider { height: 1px; background: #f1f5f9; margin: 16px 0; }

.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid #f8fafc;
}
.info-label { color: #64748b; font-size: 13px; }
.info-value { font-weight: 600; color: #1a2744; font-size: 13px; }

.section-title { color: #0f4f4f; font-size: 16px; margin-bottom: 16px; }

.score {
  font-size: 56px;
  text-align: center;
  font-weight: 800;
  line-height: 1;
  margin: 16px 0 8px;
}
.score-max { font-size: 20px; color: #94a3b8; font-weight: 400; }
.score-green { color: #15803d; }
.score-orange { color: #b45309; }
.score-red { color: #b91c1c; }

.progress {
  height: 10px;
  background: #f1f5f9;
  border-radius: 10px;
  margin: 8px 0 16px;
}
.progress-bar {
  height: 100%;
  background: #0f4f4f;
  border-radius: 10px;
  transition: width 0.5s ease;
}

.risk {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
  color: #374151;
  font-size: 14px;
}

.badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}
.green { background: #dcfce7; color: #15803d; }
.orange { background: #fef3c7; color: #b45309; }
.red { background: #fee2e2; color: #b91c1c; }
.gray { background: #f1f5f9; color: #64748b; }

.details { margin-bottom: 16px; }
.details h4 { color: #0f4f4f; font-size: 14px; margin-bottom: 10px; }
.detail-row {
  display: flex;
  justify-content: space-between;
  padding: 6px 0;
  font-size: 13px;
  color: #374151;
  border-bottom: 1px solid #f8fafc;
}
.detail-val { font-weight: 700; color: #0f4f4f; }

.report {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1.5px solid #e2e8f0;
  font-size: 13px;
  resize: vertical;
  margin-bottom: 16px;
  box-sizing: border-box;
  color: #374151;
}
.report:focus { outline: none; border-color: #0d9488; }

.actions { display: flex; gap: 10px; flex-wrap: wrap; }

.btn {
  padding: 9px 18px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 600;
  border: none;
}
.btn.primary { background: #0f4f4f; color: white; }
.btn.primary:hover { background: #154a3e; }
.btn.outline { background: white; border: 1.5px solid #0f4f4f; color: #0f4f4f; }

.loading { text-align: center; padding: 40px; color: #94a3b8; }
.error-msg { background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; }
.success-msg { margin-top: 10px; color: #15803d; font-weight: 600; font-size: 13px; }
</style>