<template>
  <div class="chart-container">
    <canvas id="scoreChart"></canvas>
  </div>
</template>

<script>
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

export default {
  name: "ScoreChart",

  mounted() {
    const ctx = document.getElementById("scoreChart");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Ali", "Sara", "Mohamed", "Amira"],
        datasets: [
          {
            label: "Score Clients",
            data: [85, 60, 40, 75],
            backgroundColor: [
              "#2ecc71",
              "#3498db",
              "#e74c3c",
              "#f1c40f"
            ]
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true
          }
        }
      }
    });
  }
};
</script>

<style>
.chart-container{
width:600px;
margin:auto;
}
</style>