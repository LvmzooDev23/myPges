<script setup>
import { computed, onMounted, ref } from 'vue'
import client from '../../api/client'
import DashboardStats from '../../components/DashboardStats.vue'
import UiCard from '../../components/ui/UiCard.vue'
import DataTable from '../../components/ui/DataTable.vue'
import ApplicationsBarChart from '../../components/charts/ApplicationsBarChart.vue'

const dash = ref(null)

onMounted(async () => {
  const { data } = await client.get('/admin/dashboard')
  dash.value = data
})

const statItems = computed(() => {
  if (!dash.value) return []
  return [
    { label: 'Étudiants', value: dash.value.total_students },
    { label: 'Entreprises', value: dash.value.total_companies },
    { label: 'Offres de stage', value: dash.value.total_internships },
    { label: 'Candidatures acceptées', value: dash.value.accepted_applications },
  ]
})

const chartRows = computed(() => dash.value?.applications_per_internship || [])
</script>

<template>
  <div class="space-y-8">
    <div>
      <p class="text-sm font-medium text-brand-600">Vue globale</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Indicateurs clés</h1>
    </div>

    <DashboardStats v-if="dash" :stats="statItems" />

    <UiCard v-if="dash" title="Candidatures par offre" subtitle="Répartition du volume de candidatures">
      <ApplicationsBarChart :rows="chartRows" />
    </UiCard>

    <UiCard
      v-if="dash?.applications_per_internship?.length"
      title="Détail par offre"
      subtitle="Tableau des candidatures reçues"
    >
      <DataTable>
        <template #head>
          <th class="px-4 py-3">Offre</th>
          <th class="px-4 py-3 text-right">Candidatures</th>
        </template>
        <template #body>
          <tr
            v-for="row in dash.applications_per_internship"
            :key="row.id"
            class="border-b border-slate-100 hover:bg-slate-50/80"
          >
            <td class="px-4 py-3 font-medium text-slate-900">{{ row.title }}</td>
            <td class="px-4 py-3 text-right tabular-nums text-slate-700">{{ row.applications_count }}</td>
          </tr>
        </template>
      </DataTable>
    </UiCard>
  </div>
</template>
