<script setup>
import { onMounted, ref } from 'vue'
import client from '../api/client'
import InternshipCard from '../components/InternshipCard.vue'
import UiCard from '../components/ui/UiCard.vue'
import DataTable from '../components/ui/DataTable.vue'
import UiBadge from '../components/ui/UiBadge.vue'

const items = ref([])
const filters = ref({ q: '', location: '', type: '' })
const loading = ref(true)

async function load(page = 1) {
  loading.value = true
  const { data } = await client.get('/internships', {
    params: { ...filters.value, page },
  })
  items.value = data.data || []
  loading.value = false
}

onMounted(() => load())

function search() {
  load(1)
}

const typeLabel = { remote: 'Télétravail', onsite: 'Sur site', hybrid: 'Hybride' }
</script>

<template>
  <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Catalogue des stages</h1>
        <p class="mt-1 text-sm text-slate-500">Filtrez et explorez les offres publiées par les entreprises partenaires.</p>
      </div>
    </div>

    <UiCard class="mt-8" title="Filtres" subtitle="Affinez votre recherche">
      <form class="flex flex-wrap gap-3" @submit.prevent="search">
        <input
          v-model="filters.q"
          placeholder="Mots-clés, compétences…"
          class="min-w-[200px] flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
        />
        <input
          v-model="filters.location"
          placeholder="Ville ou région"
          class="min-w-[160px] rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
        />
        <select
          v-model="filters.type"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
        >
          <option value="">Tous les modes</option>
          <option value="remote">Télétravail</option>
          <option value="onsite">Sur site</option>
          <option value="hybrid">Hybride</option>
        </select>
        <button
          type="submit"
          class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
        >
          Appliquer
        </button>
      </form>
    </UiCard>

    <div v-if="loading" class="mt-10 flex justify-center py-16">
      <div class="h-10 w-10 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>

    <template v-else>
      <!-- Tableau desktop -->
      <div class="mt-8 hidden lg:block">
        <DataTable>
          <template #head>
            <th class="px-4 py-3 font-semibold">Offre</th>
            <th class="px-4 py-3 font-semibold">Entreprise</th>
            <th class="px-4 py-3 font-semibold">Lieu</th>
            <th class="px-4 py-3 font-semibold">Type</th>
            <th class="px-4 py-3 font-semibold">Début</th>
            <th class="px-4 py-3 text-right font-semibold">Action</th>
          </template>
          <template #body>
            <tr
              v-for="i in items"
              :key="i.id"
              class="border-b border-slate-100 transition hover:bg-slate-50/80"
            >
              <td class="px-4 py-3 font-medium text-slate-900">{{ i.title }}</td>
              <td class="px-4 py-3 text-slate-600">{{ i.company?.name }}</td>
              <td class="px-4 py-3 text-slate-600">{{ i.location || '—' }}</td>
              <td class="px-4 py-3">
                <UiBadge v-if="i.type" variant="brand">{{ typeLabel[i.type] || i.type }}</UiBadge>
              </td>
              <td class="px-4 py-3 text-sm text-slate-500">{{ i.start_date || '—' }}</td>
              <td class="px-4 py-3 text-right">
                <router-link
                  :to="`/internships/${i.id}`"
                  class="text-sm font-semibold text-brand-600 hover:text-brand-700"
                >
                  Détails
                </router-link>
              </td>
            </tr>
          </template>
        </DataTable>
      </div>

      <!-- Cartes mobile / tablette -->
      <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:hidden">
        <InternshipCard v-for="i in items" :key="i.id" :internship="i" />
      </div>

      <p v-if="!items.length" class="mt-10 text-center text-slate-500">Aucune offre ne correspond à vos critères.</p>
    </template>
  </div>
</template>
