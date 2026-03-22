<script setup>
import { computed } from 'vue'
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
  rows: { type: Array, default: () => [] },
})

const chartData = computed(() => {
  const list = props.rows || []
  return {
    labels: list.map((r) => (r.title?.length > 24 ? r.title.slice(0, 22) + '…' : r.title) || '—'),
    datasets: [
      {
        label: 'Candidatures',
        data: list.map((r) => Number(r.applications_count) || 0),
        backgroundColor: 'rgba(99, 102, 241, 0.75)',
        borderRadius: 6,
        borderSkipped: false,
      },
    ],
  }
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
    tooltip: {
      backgroundColor: 'rgba(15, 23, 42, 0.92)',
      padding: 10,
      cornerRadius: 8,
    },
  },
  scales: {
    x: {
      grid: { display: false },
      ticks: { color: '#64748b', font: { size: 11 } },
    },
    y: {
      beginAtZero: true,
      grid: { color: 'rgba(148, 163, 184, 0.2)' },
      ticks: { color: '#64748b', stepSize: 1 },
    },
  },
}
</script>

<template>
  <div class="h-72 w-full sm:h-80">
    <Bar v-if="rows.length" :data="chartData" :options="chartOptions" />
    <p v-else class="flex h-full items-center justify-center text-sm text-slate-500">
      Aucune donnée pour le graphique
    </p>
  </div>
</template>
