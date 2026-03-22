<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'
import DataTable from '../../components/ui/DataTable.vue'
import ApplicationStatus from '../../components/ApplicationStatus.vue'

const items = ref([])
const meta = ref(null)
const loading = ref(true)
const error = ref('')

function formatDate(d) {
  if (!d) return '—'
  try {
    return new Date(d).toLocaleDateString('fr-FR', { dateStyle: 'medium' })
  } catch {
    return d
  }
}

async function load(page = 1) {
  loading.value = true
  error.value = ''
  try {
    const { data } = await client.get('/admin/applications', { params: { page } })
    items.value = data.data || []
    meta.value = data.meta || null
  } catch (e) {
    error.value = e.response?.data?.message || 'Chargement impossible.'
  } finally {
    loading.value = false
  }
}

onMounted(() => load(1))
</script>

<template>
  <div class="space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Administration</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Candidatures</h1>
    </div>

    <p v-if="error" class="text-sm text-rose-600">{{ error }}</p>

    <UiCard v-if="!loading" padding>
      <DataTable>
        <template #head>
          <th class="px-4 py-3">Étudiant</th>
          <th class="px-4 py-3">Offre</th>
          <th class="px-4 py-3">Entreprise</th>
          <th class="px-4 py-3">Statut</th>
          <th class="px-4 py-3">Date</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </template>
        <template #body>
          <tr v-for="a in items" :key="a.id" class="border-b border-slate-100 hover:bg-slate-50/80">
            <td class="px-4 py-3 font-medium text-slate-900">{{ a.student?.user?.name || '—' }}</td>
            <td class="px-4 py-3 text-slate-700">{{ a.internship?.title }}</td>
            <td class="px-4 py-3 text-slate-600">{{ a.internship?.company?.name }}</td>
            <td class="px-4 py-3">
              <ApplicationStatus :status="a.status" />
            </td>
            <td class="px-4 py-3 text-sm text-slate-600">{{ formatDate(a.applied_at) }}</td>
            <td class="px-4 py-3 text-right">
              <RouterLink
                :to="`/admin/applications/${a.id}`"
                class="text-sm font-semibold text-brand-600 hover:text-brand-700"
              >
                Détails
              </RouterLink>
            </td>
          </tr>
        </template>
      </DataTable>
      <p v-if="!items.length" class="py-8 text-center text-sm text-slate-500">Aucune candidature.</p>
      <div v-if="meta && meta.last_page > 1" class="mt-4 flex items-center justify-between gap-4 border-t border-slate-100 pt-4 text-sm text-slate-600">
        <span>Page {{ meta.current_page }} / {{ meta.last_page }}</span>
        <div class="flex gap-2">
          <button
            type="button"
            class="rounded-lg border border-slate-200 px-3 py-1.5 font-medium hover:bg-slate-50 disabled:opacity-40"
            :disabled="meta.current_page <= 1"
            @click="load(meta.current_page - 1)"
          >
            Précédent
          </button>
          <button
            type="button"
            class="rounded-lg border border-slate-200 px-3 py-1.5 font-medium hover:bg-slate-50 disabled:opacity-40"
            :disabled="meta.current_page >= meta.last_page"
            @click="load(meta.current_page + 1)"
          >
            Suivant
          </button>
        </div>
      </div>
    </UiCard>
    <div v-else class="flex justify-center py-16">
      <div class="h-10 w-10 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>
  </div>
</template>
