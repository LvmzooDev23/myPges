<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../api/client'
import UiCard from './ui/UiCard.vue'

const applications = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await client.get('/student/application-tracking')
    applications.value = data
  } catch (error) {
    console.error('Failed to load applications:', error)
  } finally {
    loading.value = false
  }
})

function getStatusColor(status) {
  switch (status) {
    case 'accepted':
      return 'bg-green-100 text-green-800'
    case 'rejected':
      return 'bg-red-100 text-red-800'
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    default:
      return 'bg-slate-100 text-slate-800'
  }
}

function getStatusText(status) {
  switch (status) {
    case 'accepted':
      return 'Accepté'
    case 'rejected':
      return 'Refusé'
    case 'pending':
      return 'En attente'
    default:
      return status
  }
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString('fr-FR')
}
</script>

<template>
  <UiCard title="Suivi des candidatures" subtitle="Historique complet de vos candidatures">
    <div v-if="loading" class="flex justify-center py-8">
      <div class="h-8 w-8 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>
    
    <div v-else-if="applications.length === 0" class="text-center py-8">
      <div class="text-slate-500 text-sm">
        <p>Vous n'avez pas encore postulé à des offres de stage.</p>
        <RouterLink to="/internships" class="text-brand-600 hover:text-brand-700 font-medium">
          Parcourir les offres disponibles
        </RouterLink>
      </div>
    </div>
    
    <div v-else class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
              Stage
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
              Entreprise
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
              Date
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
              Statut
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
          <tr v-for="application in applications" :key="application.id" class="hover:bg-slate-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-slate-900">
                {{ application.internship?.title }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-slate-600">
                {{ application.internship?.company?.name }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-slate-600">
                {{ formatDate(application.applied_at) }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span 
                :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  getStatusColor(application.status)
                ]"
              >
                {{ getStatusText(application.status) }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div v-if="applications.length > 5" class="mt-4 text-center">
        <RouterLink 
          to="/student/applications"
          class="text-sm font-medium text-brand-600 hover:text-brand-700"
        >
          Voir toutes les candidatures →
        </RouterLink>
      </div>
    </div>
  </UiCard>
</template>
