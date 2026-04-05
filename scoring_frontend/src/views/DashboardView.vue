<template>
  <AppLayout>

    <!-- En-tête page -->
    <div class="page-top">
      <div>
        <h1 class="page-title">Gestion des Clients</h1>
        <p class="page-sub">{{ clients.length }} client(s) enregistré(s)</p>
      </div>
      <div class="top-actions">
        <router-link to="/historique" class="btn-outline">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
          </svg>
          Voir Historique
        </router-link>
        <button class="btn-danger" @click="handleLogout">Déconnexion</button>
      </div>
    </div>

    <!-- Cartes stats rapides -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-icon-box blue">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
        <div>
          <div class="stat-num">{{ clients.length }}</div>
          <div class="stat-lbl">Total clients</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-box green">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <div>
          <div class="stat-num">{{ activeCount }}</div>
          <div class="stat-lbl">Clients actifs</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-box gray">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div>
          <div class="stat-num">{{ inactiveCount }}</div>
          <div class="stat-lbl">Clients inactifs</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-box purple">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
        </div>
        <div>
          <div class="stat-num">{{ avgIncome.toLocaleString('fr-TN') }}</div>
          <div class="stat-lbl">Revenu moyen (DT)</div>
        </div>
      </div>
    </div>

    <!-- Barre de recherche -->
    <div class="search-bar">
      <div class="search-input-wrap">
        <svg class="search-ic" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Rechercher un client..."
          class="search-input"
        />
      </div>
      <select v-model="filterStatus" class="filter-select">
        <option value="">Tous les statuts</option>
        <option value="Actif">Actif</option>
        <option value="Inactif">Inactif</option>
      </select>
    </div>

    <!-- Tableau clients -->
    <div class="table-card">
      <table class="clients-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Revenu Mensuel</th>
            <th>Statut</th>
            <th class="center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(client, index) in filteredClients"
            :key="client.id"
            class="client-row"
          >
            <td class="col-num">{{ index + 1 }}</td>
            <td>
              <div class="client-name-cell">
                <div class="avatar">{{ initials(client.nom + ' ' + client.prenom) }}</div>
                <span>{{ client.nom }} {{ client.prenom }}</span>
              </div>
            </td>
            <td class="col-email">{{ client.email || '-' }}</td>
            <td class="col-income">
              <span class="income-val">{{ (client.balance || 0).toLocaleString('fr-TN') }}</span>
              <span class="income-unit"> DT</span>
            </td>
            <td>
              <span class="status-badge" :class="client.status === 'active' ? 'status-active' : 'status-inactive'">
                <span class="status-dot"></span>
                {{ client.status === 'active' ? 'Actif' : 'Inactif' }}
              </span>
            </td>
            <td class="center">
              <button class="btn-score" @click="goToDetails(client)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                Calculer Score
              </button>
            </td>
          </tr>

          <!-- Aucun résultat -->
          <tr v-if="filteredClients.length === 0">
            <td colspan="6" class="empty-row">
              <div class="empty-state">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <p>Aucun client trouvé pour « {{ searchQuery }} »</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AppLayout from '@/components/AppLayout.vue'
import { useAuth } from '@/composables/useAuth'
import { clientService } from '@/services/clientService'

const router = useRouter()
const { logout: authLogout } = useAuth()

const searchQuery = ref('')
const filterStatus = ref('')
const loading = ref(false)
const error = ref('')

const clients = ref([])

// Fetch clients from API
const fetchClients = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await clientService.getClients()
    // Handle both paginated and non-paginated responses
    clients.value = response.data.data || response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Erreur lors du chargement des clients'
    console.error('Error fetching clients:', err)
  } finally {
    loading.value = false
  }
}

const filteredClients = computed(() =>
  clients.value.filter(c => {
    const matchSearch = 
      (c.nom || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (c.prenom || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (c.email || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchStatus = filterStatus.value ? c.status === filterStatus.value : true
    return matchSearch && matchStatus
  })
)

const activeCount   = computed(() => clients.value.filter(c => c.status === 'active').length)
const inactiveCount = computed(() => clients.value.filter(c => c.status !== 'active').length)
const avgIncome     = computed(() => {
  const sum = clients.value.reduce((a, c) => a + (c.balance || 0), 0)
  return Math.round(sum / (clients.value.length || 1))
})

function initials(name) {
  return name?.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || '??'
}

function goToDetails(client) {
  router.push({ name: 'clientDetails', params: { id: client.id } })
}

async function handleLogout() {
  await authLogout()
  router.push('/login')
}

onMounted(() => {
  fetchClients()
})
</script>

<style scoped>
.page-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 24px;
}

.page-title {
  font-size: 22px;
  font-weight: 700;
  color: #1a2744; /* ← noir/dark */
  margin: 0 0 3px;
}
.page-sub {
  font-size: 13px;
  color: #94a3b8;
  margin: 0;
}

.top-actions {
  display: flex;
  gap: 10px;
}

.btn-outline {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 9px 16px;
  border: 1.5px solid #0f4f4f;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  color: #0f4f4f;
  text-decoration: none;
  background: white;
  transition: all 0.15s;
}

.btn-outline:hover { background: #f0f9f9; }

.btn-danger {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 9px 16px;
  background: #c82333;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  color: white;
  border: none;
  cursor: pointer;
}

.btn-danger:hover { background: #a71d2a; }

.btn-primary {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 9px 16px;
  background:#0f4f4f;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  color: white;
  text-decoration: none;
  transition: background 0.15s;
}

.btn-primary:hover { background:#0f4f4f; }

/* ─── Stats ─────────────────────────────────────────────────── */
.stats-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-bottom: 20px;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 18px 20px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 1px 4px rgba(15, 79, 79, 0.06);
  border: 1px solid #e0f2f1;
}

.stat-icon-box {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.stat-icon-box.blue   { background: #e0f9f5; color: #0d9488; }
.stat-icon-box.green  { background: #f0fdf4; color: #16a34a; }
.stat-icon-box.gray   { background: #f8fafc; color: #64748b; }
.stat-icon-box.purple { background: #f5f3ff; color: #7c3aed; }

.stat-num { font-size: 24px; font-weight: 800; color: #0f4f4f; line-height: 1.1; }
.stat-lbl { font-size: 12px; color: #94a3b8; margin-top: 2px; }

/* ─── Recherche ─────────────────────────────────────────────── */
.search-bar {
  display: flex;
  gap: 12px;
  margin-bottom: 16px;
}

.search-input-wrap {
  position: relative;
  flex: 1;
  max-width: 420px;
}

.search-ic {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  pointer-events: none;
}

.search-input {
  width: 100%;
  padding: 9px 12px 9px 36px;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  font-size: 13.5px;
  outline: none;
  background: white;
  box-sizing: border-box;
  transition: border-color 0.15s;
  color: #0f4f4f;
}

.search-input:focus { border-color: #0d9488; }

.filter-select {
  padding: 9px 12px;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  font-size: 13px;
  background: white;
  color: #374151;
  outline: none;
  cursor: pointer;
}

.filter-select:focus { border-color: #0d9488; }

/* ─── Tableau ───────────────────────────────────────────────── */
.table-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 1px 4px rgba(15, 79, 79, 0.06);
  border: 1px solid #e0f2f1;
}

.clients-table {
  width: 100%;
  border-collapse: collapse;
}

.clients-table thead tr {
  background: #0f4f4f; /* ← teal foncé */
}

.clients-table thead th {
  padding: 13px 16px;
  text-align: left;
  font-size: 11.5px;
  font-weight: 600;
  color: #99cece;      /* ← teal clair */
  letter-spacing: 0.06em;
  text-transform: uppercase;
  border: none;
}

.clients-table thead th.center { text-align: center; }

.clients-table tbody td {
  padding: 13px 16px;
  font-size: 13.5px;
  color: #374151;
  border-bottom: 1px solid #f1f5f9;
}

.clients-table tbody tr:last-child td { border-bottom: none; }

.client-row { transition: background 0.12s; }
.client-row:hover { background: #f0faf9; }

.col-num   { color: #94a3b8; font-size: 12px; width: 40px; }
.col-email { color: #64748b; }
.center    { text-align: center; }

.client-name-cell {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
  color: #1a2744; /* ← noir/dark */
}
.avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #ccfbf1;
  color: #0f4f4f;
  font-size: 12px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.income-val { font-weight: 600; color: #1a2744; /* ← noir/dark */ }
.income-unit { color: #94a3b8; font-size: 12px; }

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-active   { background: #dcfce7; color: #15803d; }
.status-inactive { background: #f1f5f9; color: #64748b; }

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: currentColor;
}

.btn-score {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background:#0f4f4f;
  color: white;
  border: none;
  padding: 7px 14px;
  border-radius: 7px;
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s;
}
.btn-score:hover { background:#0f4f4f; }

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

@media (max-width: 900px) {
  .stats-row { grid-template-columns: repeat(2, 1fr); }
}
</style>