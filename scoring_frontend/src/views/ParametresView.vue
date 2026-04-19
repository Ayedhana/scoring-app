<template>
  <AppLayout>
    <div class="page-top">
      <h1 class="page-title">Paramètres</h1>
      <p class="page-sub">Configuration du moteur de scoring</p>
    </div>

    <!-- Chargement -->
    <div v-if="loading" class="loading">Chargement des critères...</div>

    <!-- Erreur -->
    <div v-else-if="error" class="error-msg">{{ error }}</div>

    <!-- Contenu -->
    <div v-else class="params-card">
      <div class="card-header">
        <h2>Critères de Scoring</h2>
        <span class="total-badge" :class="totalClass">
          Total : {{ totalPonderation }}%
        </span>
      </div>

      <!-- Avertissement si total ≠ 100 -->
      <div v-if="totalPonderation !== 100" class="warning-msg">
        ⚠️ La somme des pondérations doit être égale à 100% (actuellement {{ totalPonderation }}%)
      </div>

      <!-- Liste des critères -->
      <div class="criteres-list">
        <div
          v-for="critere in criteres"
          :key="critere.id"
          class="critere-item"
        >
          <div class="critere-info">
            <span class="critere-nom">{{ critere.nom }}</span>
            <span class="critere-desc">{{ critere.description }}</span>
          </div>

          <div class="critere-controls">
            <div class="input-group">
              <input
                type="number"
                v-model.number="critere.ponderation"
                min="0"
                max="100"
                class="ponderation-input"
              />
              <span class="pct">%</span>
            </div>
            <div class="mini-progress">
              <div
                class="mini-bar"
                :style="{ width: critere.ponderation + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bouton sauvegarder -->
      <div class="card-footer">
        <button
          class="btn-save"
          @click="sauvegarder"
          :disabled="totalPonderation !== 100 || saving"
        >
          {{ saving ? 'Sauvegarde...' : '💾 Sauvegarder' }}
        </button>
      </div>

      <div v-if="successMsg" class="success-msg">{{ successMsg }}</div>
      <div v-if="errorMsg" class="error-msg-inline">{{ errorMsg }}</div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '@/components/AppLayout.vue'
import { critereService } from '@/services/critereService'

const criteres = ref([])
const loading = ref(false)
const saving = ref(false)
const error = ref('')
const successMsg = ref('')
const errorMsg = ref('')

// ✅ Calcul correct du total
const totalPonderation = computed(() => {
  const sum = criteres.value.reduce((acc, c) => {
    const val = parseFloat(String(c.ponderation).replace(',', '.')) || 0
    return acc + val
  }, 0)
  return Math.round(sum)
})

const totalClass = computed(() =>
  totalPonderation.value === 100 ? 'badge-green' : 'badge-red'
)

// ✅ Chargement correct des critères
onMounted(async () => {
  loading.value = true
  try {
    const response = await critereService.getCriteres()
    // Convertir les virgules en points pour les décimaux
    criteres.value = response.data.map(c => ({
      ...c,
      ponderation: parseFloat(String(c.ponderation).replace(',', '.')) || 0
    }))
  } catch (err) {
    error.value = 'Erreur lors du chargement des critères'
    console.error(err)
  } finally {
    loading.value = false
  }
})

// ✅ Sauvegarde correcte
async function sauvegarder() {
  saving.value = true
  successMsg.value = ''
  errorMsg.value = ''

  try {
    await critereService.updateAll(
      criteres.value.map(c => ({
        id: c.id,
        ponderation: parseFloat(c.ponderation)
      }))
    )
    successMsg.value = '✅ Paramètres sauvegardés avec succès !'
    setTimeout(() => successMsg.value = '', 3000)
  } catch (err) {
    errorMsg.value = err.response?.data?.message || '❌ Erreur lors de la sauvegarde'
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.page-title { font-size: 22px; font-weight: 700; color: #1a2744; }
.page-sub { color: #94a3b8; margin-bottom: 24px; }

.params-card {
  background: white;
  border-radius: 12px;
  border: 1px solid #e0f2f1;
  box-shadow: 0 1px 4px rgba(15,79,79,0.06);
  max-width: 700px;
  overflow: hidden;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #f1f5f9;
}

.card-header h2 {
  color: #0f4f4f;
  font-size: 16px;
  margin: 0;
}

.total-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
}
.badge-green { background: #dcfce7; color: #15803d; }
.badge-red { background: #fee2e2; color: #b91c1c; }

.warning-msg {
  margin: 12px 24px 0;
  padding: 10px 14px;
  background: #fef3c7;
  color: #b45309;
  border-radius: 8px;
  font-size: 13px;
}

.criteres-list { padding: 16px 24px; }

.critere-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 0;
  border-bottom: 1px solid #f8fafc;
  gap: 20px;
}
.critere-item:last-child { border-bottom: none; }

.critere-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  flex: 1;
}

.critere-nom { font-weight: 600; color: #1a2744; font-size: 14px; }
.critere-desc { font-size: 12px; color: #94a3b8; }

.critere-controls {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 6px;
  min-width: 140px;
}

.input-group {
  display: flex;
  align-items: center;
  gap: 6px;
}

.ponderation-input {
  width: 70px;
  padding: 7px 10px;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #0f4f4f;
  text-align: center;
  outline: none;
}
.ponderation-input:focus { border-color: #0d9488; }

.pct { color: #64748b; font-weight: 600; }

.mini-progress {
  width: 120px;
  height: 6px;
  background: #f1f5f9;
  border-radius: 10px;
}
.mini-bar {
  height: 100%;
  background: #0f4f4f;
  border-radius: 10px;
  transition: width 0.3s ease;
}

.card-footer {
  padding: 20px 24px;
  border-top: 1px solid #f1f5f9;
}

.btn-save {
  background: #0f4f4f;
  color: white;
  border: none;
  padding: 10px 24px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s;
}
.btn-save:hover:not(:disabled) { background: #154a3e; }
.btn-save:disabled { opacity: 0.5; cursor: not-allowed; }

.loading { text-align: center; padding: 40px; color: #94a3b8; }
.success-msg { margin: 0 24px 16px; color: #15803d; font-weight: 600; font-size: 13px; }
.error-msg { color: #b91c1c; padding: 40px; text-align: center; }
.error-msg-inline { margin: 0 24px 16px; color: #b91c1c; font-size: 13px; }
</style>