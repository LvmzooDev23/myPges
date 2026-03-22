<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'
import DataTable from '../../components/ui/DataTable.vue'
import UiBadge from '../../components/ui/UiBadge.vue'

const router = useRouter()
const items = ref([])
const loading = ref(true)
const error = ref('')

async function load() {
  loading.value = true
  error.value = ''
  try {
    const { data } = await client.get('/company/internships')
    items.value = data.data || data
  } catch (e) {
    error.value = e.response?.data?.message || 'Impossible de charger les offres.'
  } finally {
    loading.value = false
  }
}

onMounted(load)

async function remove(i) {
  if (!confirm(`Supprimer l’offre « ${i.title} » ?`)) return
  try {
    await client.delete(`/company/internships/${i.id}`)
    await load()
  } catch (e) {
    alert(e.response?.data?.message || 'Suppression impossible')
  }
}

function statusVariant(s) {
  if (s === 'published') return 'success'
  if (s === 'draft') return 'warning'
  return 'neutral'
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-brand-600">Offres</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Gérer les stages</h1>
      </div>
      <button
        type="button"
        class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500"
        @click="router.push('/company/internships/new')"
      >
        Nouvelle offre
      </button>
    </div>

    <p v-if="error" class="text-sm text-rose-600">{{ error }}</p>

    <UiCard v-if="!loading" padding>
      <DataTable>
        <template #head>
          <th class="px-4 py-3">Titre</th>
          <th class="px-4 py-3">Lieu</th>
          <th class="px-4 py-3">Statut</th>
          <th class="px-4 py-3">Échéance</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </template>
        <template #body>
          <tr v-for="i in items" :key="i.id" class="border-b border-slate-100 hover:bg-slate-50/80">
            <td class="px-4 py-3 font-medium text-slate-900">{{ i.title }}</td>
            <td class="px-4 py-3 text-slate-600">{{ i.location || '—' }}</td>
            <td class="px-4 py-3">
              <UiBadge :variant="statusVariant(i.status)">{{ i.status }}</UiBadge>
            </td>
            <td class="px-4 py-3 text-sm text-slate-600">{{ i.deadline || '—' }}</td>
            <td class="px-4 py-3 text-right">
              <button
                type="button"
                class="mr-2 text-sm font-semibold text-brand-600 hover:text-brand-700"
                @click="router.push(`/company/internships/${i.id}/edit`)"
              >
                Modifier
              </button>
              <button type="button" class="text-sm font-semibold text-rose-600 hover:text-rose-700" @click="remove(i)">
                Supprimer
              </button>
            </td>
          </tr>
        </template>
      </DataTable>
      <p v-if="!items.length" class="py-8 text-center text-sm text-slate-500">Aucune offre pour le moment.</p>
    </UiCard>
    <div v-else class="flex justify-center py-16">
      <div class="h-10 w-10 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>
  </div>
</template>
