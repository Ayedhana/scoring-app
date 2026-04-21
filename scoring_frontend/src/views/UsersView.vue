<template>
  <div>
    <AppLayout>
      <div class="page-top">
        <div>
          <h1 class="page-title">Gestion des Utilisateurs</h1>
          <p class="page-sub">{{ users?.length ?? 0 }} utilisateur(s)</p>
        </div>
       <button class="btn-add" @click="router.push('/users/create')">
  + Nouvel Utilisateur
</button>
      </div>

      <div v-if="loading" class="loading">Chargement...</div>
      <div v-else-if="error" class="error-msg">{{ error }}</div>

      <div v-else class="table-card">
        <table class="users-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Email</th>
              <th>Rôle</th>
              <th>Statut</th>
              <th>Créé le</th>
              <th class="center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users" :key="user.id" class="user-row">
              <td class="col-num">{{ index + 1 }}</td>
              <td class="col-name">
                <div class="user-cell">
                  <div class="avatar">{{ user.name?.charAt(0).toUpperCase() }}</div>
                  <span>{{ user.name }}</span>
                </div>
              </td>
              <td class="col-email">{{ user.email }}</td>
              <td>
                <span :class="['role-badge', getRoleClass(user.role)]">
                  {{ formatRole(user.role) }}
                </span>
              </td>
              <td>
                <span :class="['status-badge', user.is_active ? 'status-active' : 'status-inactive']">
                  <span class="status-dot"></span>
                  {{ user.is_active ? 'Actif' : 'Inactif' }}
                </span>
              </td>
              <td class="col-date">{{ formatDate(user.created_at) }}</td>
              <td class="center">
                <div class="actions">
                 <button class="btn-edit" @click="goToEdit(user)">✏️</button>
                  <button
                    type="button"
                    :class="user.is_active ? 'btn-deactivate' : 'btn-activate'"
                    @click="toggleStatus(user)"
                  >
                    {{ user.is_active ? '🔒' : '🔓' }}
                  </button>
                  <button class="btn-delete" type="button" @click="confirmDelete(user)">🗑️</button>
                </div>
              </td>
            </tr>

            <tr v-if="!users || users.length === 0">
              <td colspan="7" class="empty-row">
                <div class="empty-state">
                  <p>Aucun utilisateur trouvé</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </AppLayout>

    <Teleport to="body">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal">
          <h2>{{ editingUser ? 'Modifier Utilisateur' : 'Nouvel Utilisateur' }}</h2>

          <div class="form-group">
            <label>Nom</label>
            <input v-model="form.name" type="text" placeholder="Nom complet" />
          </div>

          <div class="form-group">
            <label>Email</label>
            <input v-model="form.email" type="email" placeholder="email@exemple.com" />
          </div>

          <div class="form-group">
            <label>{{ editingUser ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe' }}</label>
            <input v-model="form.password" type="password" placeholder="••••••••" />
          </div>

          <div class="form-group">
            <label>Rôle</label>
            <select v-model="form.role">
              <option value="admin">Administrateur</option>
              <option value="analyst">Analyste Crédit</option>
              <option value="auditor">Auditeur</option>
            </select>
          </div>

          <div v-if="formError" class="form-error">{{ formError }}</div>

          <div class="modal-actions">
            <button class="btn-cancel" type="button" @click="closeModal">Annuler</button>
            <button class="btn-save" type="button" @click="saveUser" :disabled="saving">
              {{ saving ? 'Sauvegarde...' : 'Sauvegarder' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '@/components/AppLayout.vue'
import { userService } from '@/services/userService'
import { useRouter } from 'vue-router'
const router = useRouter()

// ✅ Initialisé avec un tableau vide
const users = ref([])
const loading = ref(false)
const error = ref('')
const showModal = ref(false)
const editingUser = ref(null)
const saving = ref(false)
const formError = ref('')

const form = ref({
  name: '',
  email: '',
  password: '',
  role: 'analyst'
})

onMounted(() => fetchUsers())

async function fetchUsers() {
  loading.value = true
  error.value = ''
  try {
    const res = await userService.getUsers()
    // ✅ Assure-toi que c'est toujours un tableau
    users.value = Array.isArray(res.data) ? res.data : (res.data?.data || [])
  } catch (err) {
    error.value = 'Erreur lors du chargement des utilisateurs'
    users.value = []
    console.error(err)
  } finally {
    loading.value = false
  }
}

function goToEdit(user) {
  router.push(`/users/${user.id}/edit`)
}

function closeModal() {
  showModal.value = false
  editingUser.value = null
}

async function saveUser() {
  formError.value = ''
  saving.value = true
  try {
    const data = { ...form.value }
    if (editingUser.value) {
      if (!data.password) delete data.password
      await userService.updateUser(editingUser.value.id, data)
    } else {
      await userService.createUser(data)
    }
    await fetchUsers()
    closeModal()
  } catch (err) {
    formError.value = err.response?.data?.message || 'Erreur lors de la sauvegarde'
  } finally {
    saving.value = false
  }
}

async function toggleStatus(user) {
  try {
    await userService.toggleStatus(user.id)
    await fetchUsers()
  } catch (err) {
    console.error(err)
  }
}

async function confirmDelete(user) {
  if (confirm(`Supprimer ${user.name} ?`)) {
    try {
      await userService.deleteUser(user.id)
      await fetchUsers()
    } catch (err) {
      alert(err.response?.data?.message || 'Erreur lors de la suppression')
    }
  }
}

function formatRole(role) {
  if (role === 'admin') return 'Admin'
  if (role === 'analyst') return 'Analyste'
  return 'Auditeur'
}

function getRoleClass(role) {
  if (role === 'admin') return 'role-admin'
  if (role === 'analyst') return 'role-analyst'
  return 'role-auditor'
}

function formatDate(dateStr) {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('fr-TN', {
    year: 'numeric', month: '2-digit', day: '2-digit'
  })
}
</script>

<style scoped>
.page-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 24px;
}
.page-title { font-size: 22px; font-weight: 700; color: #1a2744; margin: 0 0 3px; }
.page-sub { font-size: 13px; color: #94a3b8; margin: 0; }

.btn-add {
  background: #0f4f4f;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}
.btn-add:hover { background: #154a3e; }

.table-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e0f2f1;
  box-shadow: 0 1px 4px rgba(15,79,79,0.06);
}

.users-table { width: 100%; border-collapse: collapse; }
.users-table thead tr { background: #0f4f4f; }
.users-table thead th {
  padding: 13px 16px;
  text-align: left;
  font-size: 11.5px;
  font-weight: 600;
  color: #99cece;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  border: none;
}
.users-table thead th.center { text-align: center; }
.users-table tbody td {
  padding: 13px 16px;
  font-size: 13.5px;
  color: #374151;
  border-bottom: 1px solid #f1f5f9;
}
.users-table tbody tr:last-child td { border-bottom: none; }
.user-row { transition: background 0.12s; }
.user-row:hover { background: #f0faf9; }

.col-num { color: #94a3b8; font-size: 12px; width: 40px; }
.col-email { color: #64748b; }
.col-date { color: #64748b; font-size: 13px; }
.center { text-align: center; }

.user-cell {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
  color: #1a2744;
}

.avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #ccfbf1;
  color: #0f4f4f;
  font-size: 13px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}

.role-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}
.role-admin   { background: #ede9fe; color: #6d28d9; }
.role-analyst { background: #e0f2fe; color: #0369a1; }
.role-auditor { background: #fef3c7; color: #b45309; }

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
.status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

.actions { display: flex; gap: 6px; justify-content: center; }
.btn-edit, .btn-deactivate, .btn-activate, .btn-delete {
  padding: 5px 8px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
}
.btn-edit       { background: #e0f2fe; }
.btn-deactivate { background: #fef3c7; }
.btn-activate   { background: #dcfce7; }
.btn-delete     { background: #fee2e2; }

.btn-save:disabled { opacity: 0.5; cursor: not-allowed; }

.loading { text-align: center; padding: 40px; color: #94a3b8; }
.error-msg { background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; }
.empty-row { padding: 0 !important; }
.empty-state { display: flex; justify-content: center; padding: 40px; color: #94a3b8; }
</style>