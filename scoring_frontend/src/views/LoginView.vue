<template>
  <div class="login-page">
    <div class="bg-blur"></div>

    <!-- Bouton dark/light mode -->
    <ThemeToggle class="toggle-btn" />

    <div class="login-container">

      <!-- Logo -->
      <div class="logo">
        <img src="@/assets/btl-logo.png" alt="BTL Logo" />
      </div>

      <!-- Title -->
      <h2>CONNECTEZ-VOUS À VOTRE COMPTE</h2>
      <p class="subtitle">Saisissez vos informations d'identification</p>

      <!-- Error Message -->
      <div v-if="errorMsg" class="alert alert-danger" role="alert">
        {{ errorMsg }}
      </div>

      <!-- Form -->
      <form @submit.prevent="handleLogin">

        <div class="input-group">
          <span class="icon">✉️</span>
          <input
            type="email"
            v-model="email"
            placeholder="Adresse e-mail"
            autocomplete="email"
          />
        </div>

        <div class="input-group">
          <span class="icon">🔒</span>
          <input
            type="password"
            v-model="password"
            placeholder="Mot de passe"
            autocomplete="current-password"
          />
        </div>

        <button type="submit" :disabled="loading">
          {{ loading ? "Connexion en cours..." : "Connexion →" }}
        </button>
      </form>

      <p class="small">
        Pas encore de compte ? <router-link to="/register">Créer un compte</router-link>
      </p>

    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import { useAuth } from "@/composables/useAuth"
import ThemeToggle from "@/components/ThemeToggle.vue"

const router = useRouter()
const { login, error: authError, loading } = useAuth()

const email = ref("")
const password = ref("")
const errorMsg = ref("")

const handleLogin = async () => {
  errorMsg.value = ""
  if (!email.value || !password.value) {
    errorMsg.value = "Veuillez remplir tous les champs"
    return
  }

  const result = await login(email.value, password.value)

  if (result.success) {
    router.push('/')
  } else {
    errorMsg.value = result.error || "Connexion échouée"
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden;
}

/* Background flouté dans un div séparé */
.bg-blur {
  position: absolute;
  inset: 0;
  background: url('@/assets/btl banque.jpg') no-repeat center center / cover;
  filter: blur(10px);
  transform: scale(1.05);
  z-index: 0;
}

/* Overlay sombre */
.login-page::after {
  content: "";
  position: absolute;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.25);
  z-index: 1;
}

/* Bouton toggle positionné en haut à droite */
.toggle-btn {
  position: absolute;
  top: 20px;
  right: 20px;
  z-index: 3;
}

.login-container {
  width: 600px;
  background: var(--card-bg, white);
  color: var(--text-primary, #1a1a1a);
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
  position: relative;
  z-index: 2;
}

.logo {
  text-align: left;
  margin-bottom: 20px;
}

.logo img {
  height: 60px;
}

h2 {
  text-align: center;
  font-weight: 600;
  margin-bottom: 5px;
}

.subtitle {
  text-align: center;
  color: var(--text-secondary, #999);
  margin-bottom: 25px;
}

.input-group {
  display: flex;
  align-items: center;
  border: 1px solid var(--border-color, #ddd);
  margin-bottom: 20px;
  padding: 10px;
  background: var(--input-bg, #f9f9f9);
}

.icon {
  margin-right: 10px;
}

input {
  border: none;
  outline: none;
  width: 100%;
  background: transparent;
  font-size: 14px;
  color: var(--text-primary, #1a1a1a);
}

button {
  width: 100%;
  padding: 14px;
  background: #0e5f66;
  color: white;
  border: none;
  font-size: 16px;
  cursor: pointer;
  transition: 0.3s;
}

button:hover {
  background: #08494e;
}

button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.alert {
  padding: 12px 16px;
  margin-bottom: 20px;
  border-radius: 8px;
  font-size: 14px;
}

.alert-danger {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}
</style>