<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import ApplicationStatus from '../../components/ApplicationStatus.vue'
import UiCard from '../../components/ui/UiCard.vue'
import DataTable from '../../components/ui/DataTable.vue'

const internships = ref([])
const selectedId = ref(null)
const applications = ref([])

onMounted(async () => {
  const { data } = await client.get('/company/internships')
  internships.value = data.data || data
})

async function loadApps() {
  if (!selectedId.value) {
    applications.value = []
    return
  }
  const { data } = await client.get(`/company/internships/${selectedId.value}/applications`)
  applications.value = data.data || data
}

async function setStatus(app, status) {
  await client.patch(`/company/applications/${app.id}`, { status })
  await loadApps()
}
</script>

<template>
  <div class="space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Recrutement</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Candidatures reçues</h1>
    </div>

    <UiCard title="Sélectionner une offre" subtitle="Consultez et traitez les candidatures par offre">
      <select
        v-model="selectedId"
        class="w-full max-w-xl rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
        @change="loadApps"
      >
        <option :value="null">Choisir une offre…</option>
        <option v-for="i in internships" :key="i.id" :value="i.id">{{ i.title }}</option>
      </select>
    </UiCard>

    <UiCard v-if="selectedId" title="Liste des candidats" :subtitle="`Offre #${selectedId}`">
      <DataTable>
        <template #head>
          <th class="px-4 py-3">Candidat</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Statut</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </template>
        <template #body>
          <tr
            v-for="a in applications"
            :key="a.id"
            class="border-b border-slate-100 hover:bg-slate-50/80"
          >
            <td class="px-4 py-3 font-medium text-slate-900">{{ a.student?.user?.name }}</td>
            <td class="px-4 py-3 text-sm text-slate-600">{{ a.student?.user?.email }}</td>
            <td class="px-4 py-3">
              <ApplicationStatus :status="a.status" />
            </td>
            <td class="px-4 py-3 text-right">
              <div v-if="a.status === 'pending'" class="flex justify-end gap-2">
                <button
                  type="button"
                  class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-500"
                  @click="setStatus(a, 'accepted')"
                >
                  Accepter
                </button>
                <button
                  type="button"
                  class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                  @click="setStatus(a, 'rejected')"
                >
                  Refuser
                </button>
              </div>
              <span v-else class="text-xs text-slate-400">—</span>
            </td>
          </tr>
        </template>
      </DataTable>
    </UiCard>
  </div>
</template>
