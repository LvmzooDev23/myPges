<script setup>
import { computed, onMounted, ref } from 'vue'
import client from '../../api/client'
import DashboardStats from '../../components/DashboardStats.vue'

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
    { label: 'Stages', value: dash.value.total_internships },
    { label: 'Candidatures acceptées', value: dash.value.accepted_applications },
  ]
})
</script>

<template>
  <div>
    <h1 class="text-xl font-bold">Administration</h1>
    <DashboardStats v-if="dash" class="mt-6" :stats="statItems" />
    <div v-if="dash?.applications_per_internship?.length" class="mt-8">
      <h2 class="font-semibold text-slate-800">Candidatures par offre</h2>
      <table class="mt-3 w-full text-sm">
        <thead>
          <tr class="border-b text-left">
            <th class="py-2">Offre</th>
            <th class="py-2">Candidatures</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in dash.applications_per_internship" :key="row.id" class="border-b border-slate-100">
            <td class="py-2">{{ row.title }}</td>
            <td class="py-2">{{ row.applications_count }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
