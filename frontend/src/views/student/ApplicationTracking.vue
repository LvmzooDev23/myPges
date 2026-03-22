<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../../api/client'
import ApplicationStatus from '../../components/ApplicationStatus.vue'
import UiCard from '../../components/ui/UiCard.vue'
import DataTable from '../../components/ui/DataTable.vue'

const applications = ref([])

onMounted(async () => {
  const { data } = await client.get('/student/applications')
  applications.value = data.data || data
})
</script>

<template>
  <div class="space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Suivi</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Mes candidatures</h1>
    </div>

    <!-- Desktop -->
    <UiCard class="hidden md:block" padding>
      <DataTable>
        <template #head>
          <th class="px-4 py-3">Offre</th>
          <th class="px-4 py-3">Entreprise</th>
          <th class="px-4 py-3">Statut</th>
          <th class="px-4 py-3 text-right">Action</th>
        </template>
        <template #body>
          <tr
            v-for="a in applications"
            :key="a.id"
            class="border-b border-slate-100 hover:bg-slate-50/80"
          >
            <td class="px-4 py-3 font-medium text-slate-900">{{ a.internship?.title }}</td>
            <td class="px-4 py-3 text-slate-600">{{ a.internship?.company?.name }}</td>
            <td class="px-4 py-3">
              <ApplicationStatus :status="a.status" />
            </td>
            <td class="px-4 py-3 text-right">
              <RouterLink
                :to="`/internships/${a.internship?.id}`"
                class="text-sm font-semibold text-brand-600 hover:text-brand-700"
              >
                Voir l’offre
              </RouterLink>
            </td>
          </tr>
        </template>
      </DataTable>
    </UiCard>

    <!-- Mobile -->
    <div class="space-y-3 md:hidden">
      <div
        v-for="a in applications"
        :key="a.id"
        class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-card"
      >
        <div class="flex items-start justify-between gap-2">
          <div>
            <p class="font-semibold text-slate-900">{{ a.internship?.title }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ a.internship?.company?.name }}</p>
          </div>
          <ApplicationStatus :status="a.status" />
        </div>
      </div>
    </div>
  </div>
</template>
