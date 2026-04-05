<template>
  <div class="register-page">
    <div class="register-container">
      <h2>Créez un compte</h2>
      <p class="subtitle">Entrez vos informations pour vous inscrire</p>

      <div v-if="errorMsg" class="alert alert-danger" role="alert">
        {{ errorMsg }}
      </div>

      <form @submit.prevent="handleRegister">
        <div class="input-group">
          <span class="icon">👤</span>
          <input
            type="text"
            v-model="name"
            placeholder="Nom complet"
            autocomplete="name"
          />
        </div>

        <div class="input-group">
          <span class="icon">✉️</span>
          <input
            type="email"
            v-model="email"
            placeholder="Email"
            autocomplete="email"
          />
        </div>

        <div class="input-group">
          <span class="icon">🔒</span>
          <input
            type="password"
            v-model="password"
            placeholder="Mot de passe"
            autocomplete="new-password"
          />
        </div>

        <div class="input-group">
          <span class="icon">🔒</span>
          <input
            type="password"
            v-model="passwordConfirm"
            placeholder="Confirmez le mot de passe"
            autocomplete="new-password"
          />
        </div>

        <button type="submit" :disabled="loading">
          {{ loading ? 'Inscription en cours...' : 'Créer un compte' }}
        </button>
      </form>

      <p class="small">
        Vous avez déjà un compte ? <router-link to="/login">Connexion</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { register, error: authError, loading } = useAuth()

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirm = ref('')
const errorMsg = ref('')

const handleRegister = async () => {
  errorMsg.value = ''

  if (!name.value || !email.value || !password.value || !passwordConfirm.value) {
    errorMsg.value = 'Veuillez remplir tous les champs'
    return
  }

  if (password.value !== passwordConfirm.value) {
    errorMsg.value = 'Les mots de passe ne correspondent pas'
    return
  }

  const result = await register(name.value, email.value, password.value, passwordConfirm.value)

  if (result.success) {
    router.push('/')
  } else {
    errorMsg.value = result.error || 'Échec de l’inscription'
  }
}
</script>

<style scoped>
.register-page {
  background: url('@/assets/btl banque.jpg') no-repeat center center / cover;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}

.register-page::before {
  content: ''; 
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  backdrop-filter: blur(10px);
  background-color: rgba(0, 0, 0, 0.2);
  z-index: 1;
}

.register-container {
  width: 600px;
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
  position: relative;
  z-index: 2;
}

h2 {
  text-align: center;
  margin-bottom: 10px;
}

.subtitle {
  text-align: center;
  color: #999;
  margin-bottom: 20px;
}

.input-group {
  display: flex;
  align-items: center;
  border: 1px solid #ddd;
  margin-bottom: 15px;
  padding: 10px;
  background: #f9f9f9;
}

.icon {
  margin-right: 10px;
}

input {
  border: none;
  outline: none;
  width: 100%;
  background: transparent;
}

button {
  width: 100%;
  padding: 14px;
  margin-top: 5px;
  background: #0e5f66;
  color: white;
  border: none;
  font-size: 16px;
  cursor: pointer;
}

button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.small {
  text-align: center;
  margin-top: 15px;
}

.alert {
  padding: 12px 16px;
  margin-bottom: 20px;
  border-radius: 8px;
  font-size: 14px;
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}
</style>
