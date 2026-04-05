<template>
  <AppLayout>

    <!-- HEADER -->
    <div class="page-top">
      <h1 class="page-title">Analyse Client</h1>
      <p class="page-sub">Scoring & évaluation du risque</p>
    </div>

    <div v-if="client" class="grid">

      <!-- CLIENT INFO -->
      <div class="card">

        <div class="avatar">
          {{ client.name.charAt(0) }}
        </div>

        <h3>{{ client.name }}</h3>
        <p class="email">{{ client.email }}</p>

        <div class="divider"></div>

        <div class="info">
          <p><strong>Revenu</strong> {{ client.income }} DT</p>
          <p>
            <strong>Status</strong>
            <span :class="client.status === 'Actif' ? 'badge green' : 'badge gray'">
              {{ client.status }}
            </span>
          </p>
        </div>

      </div>

      <!-- SCORE CARD -->
      <div class="card">

        <h3 class="section-title">Score Crédit</h3>

        <!-- SCORE NUMBER -->
        <div class="score">
          {{ score }}
        </div>

        <!-- PROGRESS BAR -->
        <div class="progress">
          <div class="progress-bar" :style="{ width: score + '%' }"></div>
        </div>

        <!-- RISK -->
        <div class="risk">
          <span>Risque :</span>
          <span
            :class="[
              'badge',
              riskLevel === 'Faible'
                ? 'green'
                : riskLevel === 'Moyen'
                ? 'orange'
                : 'red'
            ]"
          >
            {{ riskLevel }}
          </span>
        </div>

        <!-- DETAILS -->
        <div class="details">
          <ul>
            <li>✔ Analyse du revenu</li>
            <li>✔ Statut du client</li>
            <li>✔ Activité bancaire</li>
          </ul>
        </div>

        <!-- RAPPORT -->
        <textarea
          v-model="report"
          class="report"
          placeholder="Ajouter une analyse..."
        ></textarea>

        <!-- BUTTONS -->
        <div class="actions">
          <button class="btn primary" @click="generatePDF">PDF</button>
          <button class="btn success" @click="saveScore">Save</button>
          <button class="btn danger" @click="deleteScore">Delete</button>
          <button class="btn outline" @click="goBack">Retour</button>
        </div>

      </div>

    </div>

  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import AppLayout from "@/components/AppLayout.vue"
import jsPDF from "jspdf"

const route = useRoute()
const router = useRouter()

const client = ref(null)
const score = ref(0)
const riskLevel = ref("")
const report = ref("")

const clientsData = [
  { id: 1, name: "Ali Ben Salah", email: "ali@gmail.com", income: 3000, status: "Actif" },
  { id: 2, name: "Sami Trabelsi", email: "sami@gmail.com", income: 2500, status: "Inactif" },
  { id: 3, name: "Mouna Hamdi", email: "mouna@gmail.com", income: 4200, status: "Actif" }
]

onMounted(() => {
  const id = parseInt(route.params.id)
  client.value = clientsData.find(c => c.id === id)
  calculateScore()
})

function calculateScore(){
  if(client.value.income > 4000){
    score.value = 90
    riskLevel.value = "Faible"
  } else if(client.value.income > 2500){
    score.value = 70
    riskLevel.value = "Moyen"
  } else {
    score.value = 50
    riskLevel.value = "Élevé"
  }
}

function saveScore(){
  alert("Sauvegardé ✔")
}

function deleteScore(){
  score.value = 0
}

function goBack(){
  router.push("/")
}

function generatePDF(){
  const pdf = new jsPDF()
  pdf.text("Rapport Client", 20, 20)
  pdf.text(client.value.name, 20, 40)
  pdf.text("Score: " + score.value, 20, 50)
  pdf.save("rapport.pdf")
}
</script>

<style scoped>

/* HEADER */
.page-title {
  font-size: 22px;
  font-weight: 700;
}
.page-sub {
  color: #94a3b8;
}

/* GRID */
.grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 20px;
}

/* CARD */
.card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  border: 1px solid #e8eef6;
}

/* AVATAR */
.avatar {
  width: 60px;
  height: 60px;
  background: #1e4db7;
  color: white;
  border-radius: 50%;
  display:flex;
  align-items:center;
  justify-content:center;
  margin:auto;
  font-weight:bold;
}

/* EMAIL */
.email {
  text-align:center;
  color:gray;
}

/* DIVIDER */
.divider {
  height:1px;
  background:#eee;
  margin:15px 0;
}

/* SCORE */
.score {
  font-size: 50px;
  text-align:center;
  font-weight:bold;
  color:#1e4db7;
}

/* PROGRESS */
.progress {
  height:8px;
  background:#eee;
  border-radius:10px;
  margin:10px 0;
}
.progress-bar {
  height:100%;
  background:#1e4db7;
  border-radius:10px;
}

/* BADGE */
.badge {
  padding:5px 10px;
  border-radius:20px;
}
.green { background:#dcfce7; color:#15803d; }
.orange { background:#fef3c7; color:#b45309; }
.red { background:#fee2e2; color:#b91c1c; }
.gray { background:#e5e7eb; }

/* REPORT */
.report {
  width:100%;
  margin-top:10px;
  padding:8px;
  border-radius:8px;
  border:1px solid #ddd;
}

/* BUTTONS */
.actions {
  display:flex;
  gap:10px;
  margin-top:10px;
}
.btn {
  padding:6px 10px;
  border-radius:6px;
  cursor:pointer;
}
.primary { background:#1e4db7; color:white; }
.success { background:green; color:white; }
.danger { background:red; color:white; }
.outline { border:1px solid black; }

</style>