<template>
  <AppLayout>

    <!-- En-tête -->
    <div class="page-top">
      <div>
        <h1 class="page-title">Historique des Scores</h1>
        <p class="page-sub">{{ clients.length }} analyse(s)</p>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-card">
      <table class="clients-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nom Client</th>
            <th>Revenu</th>
            <th>Score</th>
            <th>Décision</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(client, index) in clients" :key="client.id" class="client-row">
            <td class="col-num">{{ index + 1 }}</td>
            <td class="col-name">{{ client.nom }}</td>
            <td>{{ client.revenu.toLocaleString('fr-TN') }} <span class="dt">DT</span></td>
            <td><strong class="score-val">{{ client.score }}</strong></td>
            <td>
              <span :class="['status-badge', client.score >= 70 ? 'status-active' : 'status-inactive']">
                <span class="status-dot"></span>
                {{ client.score >= 70 ? 'Accepté' : 'Refusé' }}
              </span>
            </td>
            <td class="col-date">{{ client.date }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/components/AppLayout.vue'

const clients = ref([
  { id: 1, nom: 'Ali Ben Salah',   revenu: 3000, score: 70, date: '2026-03-05' },
  { id: 2, nom: 'Ahmed Trabelsi',  revenu: 2000, score: 55, date: '2026-03-04' },
  { id: 3, nom: 'Sami Gharbi',     revenu: 1500, score: 40, date: '2026-03-03' },
])
</script>

<style scoped>
.page-top { margin-bottom: 20px; }

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

.table-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e0f2f1;
  box-shadow: 0 1px 4px rgba(15, 79, 79, 0.06);
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
  color: #99cece;       /* ← teal clair */
  letter-spacing: 0.06em;
  text-transform: uppercase;
  border: none;
}

.clients-table tbody td {
  padding: 13px 16px;
  font-size: 13.5px;
  color: #374151;
  border-bottom: 1px solid #f1f5f9;
}

.clients-table tbody tr:last-child td { border-bottom: none; }

.client-row { transition: background 0.12s; }
.client-row:hover { background: #f0faf9; }

.col-num  { color: #94a3b8; font-size: 12px; width: 40px; }
.col-name { font-weight: 500; color: #1a2744; /* ← noir/dark */ }
.col-date { color: #64748b; font-size: 13px; }

.score-val { color: #0f4f4f; font-size: 15px; }
.dt { color: #94a3b8; font-size: 12px; }

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
.status-inactive { background: #fee2e2; color: #b91c1c; }

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: currentColor;
}
</style>
