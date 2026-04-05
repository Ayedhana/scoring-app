<template>
  <div class="clients-view">
    <div class="container-fluid p-4">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="mb-1">Clients</h1>
          <p class="text-muted">Manage and view all clients</p>
        </div>
        <button class="btn btn-primary" @click="logout">Logout</button>
      </div>

      <!-- Error Alert -->
      <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ error }}
        <button type="button" class="btn-close" @click="error = ''"></button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <!-- Clients Table -->
      <div v-else-if="clients.length > 0" class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Type</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="client in clients" :key="client.id">
              <td>{{ client.id }}</td>
              <td>
                <strong>{{ client.nom }} {{ client.prenom }}</strong>
              </td>
              <td>{{ client.email || '-' }}</td>
              <td>{{ client.telephone || '-' }}</td>
              <td>
                <span class="badge bg-info">{{ client.type_client }}</span>
              </td>
              <td>
                <router-link 
                  :to="{ name: 'clientDetails', params: { id: client.id } }"
                  class="btn btn-sm btn-outline-primary"
                >
                  View Details
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- No Data -->
      <div v-else class="alert alert-info text-center py-5">
        No clients found
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import { clientService } from '@/services/clientService'

const router = useRouter()
const { logout: authLogout, user } = useAuth()

const clients = ref([])
const loading = ref(false)
const error = ref('')

const fetchClients = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await clientService.getClients()
    clients.value = response.data.data || response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load clients'
    console.error('Error fetching clients:', err)
  } finally {
    loading.value = false
  }
}

const logout = async () => {
  await authLogout()
  router.push('/login')
}

onMounted(() => {
  fetchClients()
})
</script>

<style scoped>
.clients-view {
  background-color: #f8f9fa;
  min-height: 100vh;
  padding-top: 20px;
}

h1 {
  color: #333;
  font-weight: 600;
}

.table {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table thead {
  background-color: #f1f3f5;
}

.table thead th {
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  color: #495057;
}

.btn-outline-primary {
  color: #007bff;
  border-color: #007bff;
}

.btn-outline-primary:hover {
  background-color: #007bff;
  color: white;
}
</style>
