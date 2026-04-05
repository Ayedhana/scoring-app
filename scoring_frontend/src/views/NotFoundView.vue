<template>
  <div class="notfound-wrapper">
    <!-- Background -->
    <div class="btl-background"></div>

    <!-- Overlay sombre léger -->
    <div class="overlay"></div>

    <!-- Content -->
    <div class="content">
      <div class="card-404">
        <h1 class="code">404</h1>
        <h2 class="title">Page non trouvée</h2>
        <p class="subtitle">
          La page que vous recherchez semble introuvable ou a été déplacée.
        </p>

        <button class="btn-home" @click="goHome">
          Retour à l’accueil
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router"
const router = useRouter()

function goHome() {
  router.push("/")
}
</script>

<style scoped>
/* Layout */
.notfound-wrapper {
  position: relative;
  width: 100%;
  height: 100vh;
  overflow: hidden;
}

/* Background */
.btl-background {
  position: absolute;
  width: 100%;
  height: 100%;
  background-image: url("@/assets/btl banque.jpg");
  background-size: cover;
  background-position: center;
  filter: blur(8px);
  transform: scale(1.1);
}

/* Overlay sombre */
.overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.35);
}

/* Center content */
.content {
  position: relative;
  z-index: 2;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Card style (effet glass) */
.card-404 {
  backdrop-filter: blur(15px);
  background: rgba(255, 255, 255, 0.08);
  padding: 40px 60px;
  border-radius: 15px;
  text-align: center;
  color: white;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

/* 404 plus petit et élégant */
.code {
  font-size: 5rem;
  font-weight: 600;
  color: #ff4d4f;
  margin-bottom: 10px;
}

/* Title */
.title {
  font-size: 1.6rem;
  font-weight: 500;
  margin-bottom: 10px;
}

/* Subtitle */
.subtitle {
  font-size: 0.95rem;
  opacity: 0.85;
  margin-bottom: 25px;
}

/* Button */
.btn-home {
  background: transparent;
  border: 1.5px solid #ffffff;
  color: #ffffff;
  padding: 10px 22px;
  font-size: 0.95rem;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-home:hover {
  background: #ffffff;
  color: #0f4f4f;
}
</style>