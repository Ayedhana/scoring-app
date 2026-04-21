<template>
  <AppLayout>
    <div class="page-top">
      <div>
        <h1 class="page-title">Modifier Utilisateur</h1>
        <p class="page-sub">Modifier les informations du compte</p>
      </div>
      <button class="btn-back" @click="router.push('/users')">
        ← Retour
      </button>
    </div>

    <div v-if="loading" class="loading">Chargement...</div>

    <div v-else class="form-card">
      <div class="form-group">
        <label>Nom complet</label>
        <input v-model="form.name" type="text" placeholder="Nom complet" />
      </div>

      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" placeholder="email@exemple.com" />
      </div>

      <div class="form-group">
        <label>Nouveau mot de passe (optionnel)</label>
        <input v-model="form.password" type="password" placeholder="Laisser vide pour ne pas changer" />
      </div>

      <div class="form-group">
        <label>Rôle</label>
        <select v-model="form.role">
          <option value="admin">Administrateur</option>
          <option value="analyst">Analyste Crédit</option>
          <option value="auditor">Auditeur</option>
        </select>
      </div>

      <div class="form-group">
        <label>Statut</label>
        <select v-model="form.is_active">
          <option :value="true">Actif</option>
          <option :value="false">Inactif</option>
        </select>
      </div>

      <div v-if="error" class="error-msg">{{ error }}</div>
      <div v-if="success" class="success-msg">{{ success }}</div>

      <div class="form-actions">
        <button class="btn-cancel" @click="router.push('/users')">Annuler</button>
        <button class="btn-save" @click="saveUser" :disabled="saving">
          {{ saving ? 'Sauvegarde...' : 'Sauvegarder' }}
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AppLayout from '@/components/AppLayout.vue'
import { userService } from '@/services/userService'

const router = useRouter()
const route = useRoute()
const saving = ref(false)
const loading = ref(false)
const error = ref('')
const success = ref('')

const form = ref({
  name: '',
  email: '',
  password: '',
  role: 'analyst',
  is_active: true
})

onMounted(async () => {
  loading.value = true
  try {
    const res = await userService.getUsers()
    const users = Array.isArray(res.data) ? res.data : []
    const user = users.find(u => u.id == route.params.id)
    if (user) {
      form.value = {
        name: user.name,
        email: user.email,
        password: '',
        role: user.role,
        is_active: user.is_active
      }
    }
  } catch (err) {
    error.value = 'Erreur lors du chargement'
  } finally {
    loading.value = false
  }
})

async function saveUser() {
  error.value = ''
  saving.value = true
  try {
    const data = { ...form.value }
    if (!data.password) delete data.password
    await userService.updateUser(route.params.id, data)
    success.value = '✅ Utilisateur modifié avec succès !'
    setTimeout(() => router.push('/users'), 1500)
  } catch (err) {
    error.value = err.response?.data?.message || 'Erreur lors de la modification'
  } finally {
    saving.value = false
  }
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

.btn-back {
  background: white;
  border: 1.5px solid #0f4f4f;
  color: #0f4f4f;
  padding: 9px 16px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.form-card {
  background: white;
  border-radius: 12px;
  padding: 28px;
  max-width: 560px;
  border: 1px solid #e0f2f1;
  box-shadow: 0 1px 4px rgba(15,79,79,0.06);
}

.form-group { margin-bottom: 18px; }
.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 6px;
}
.form-group input,
.form-group select {
  width: 100%;
  padding: 10px 12px;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  font-size: 13px;
  color: #374151;
  outline: none;
  box-sizing: border-box;
}
.form-group input:focus,
.form-group select:focus { border-color: #0d9488; }

.form-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  margin-top: 24px;
}

.btn-cancel {
  padding: 10px 20px;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  background: white;
  color: #374151;
  cursor: pointer;
  font-size: 13px;
}

.btn-save {
  padding: 10px 20px;
  background: #0f4f4f;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 600;
}
.btn-save:disabled { opacity: 0.5; cursor: not-allowed; }

.error-msg { background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 12px; font-size: 13px; }
.success-msg { background: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 12px; font-size: 13px; }
.loading { text-align: center; padding: 40px; color: #94a3b8; }
</style>