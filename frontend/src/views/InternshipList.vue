<script setup>
import { onMounted, ref } from 'vue'
import client from '../api/client'
import InternshipCard from '../components/InternshipCard.vue'

const items = ref([])
const meta = ref(null)
const filters = ref({ q: '', location: '', type: '' })
const loading = ref(true)

async function load(page = 1) {
  loading.value = true
  const { data } = await client.get('/internships', {
    params: { ...filters.value, page },
  })
  items.value = data.data || []
  meta.value = data.meta || null
  loading.value = false
}

onMounted(() => load())

function search() {
  load(1)
}
</script>

<template>
  <div class="mx-auto max-w-6xl px-4 py-8">
    <h1 class="text-2xl font-bold text-slate-900">Offres de stage</h1>
    <form class="mt-6 flex flex-wrap gap-3" @submit.prevent="search">
      <input
        v-model="filters.q"
        placeholder="Recherche"
        class="rounded-lg border px-3 py-2 text-sm"
      />
      <input v-model="filters.location" placeholder="Lieu" class="rounded-lg border px-3 py-2 text-sm" />
      <select v-model="filters.type" class="rounded-lg border px-3 py-2 text-sm">
        <option value="">Type</option>
        <option value="remote">Télétravail</option>
        <option value="onsite">Sur site</option>
        <option value="hybrid">Hybride</option>
      </select>
      <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm text-white">Filtrer</button>
    </form>
    <p v-if="loading" class="mt-8 text-slate-500">Chargement…</p>
    <div v-else class="mt-8 grid gap-4 md:grid-cols-2">
      <InternshipCard v-for="i in items" :key="i.id" :internship="i" />
    </div>
    <p v-if="!loading && !items.length" class="mt-8 text-slate-500">Aucune offre.</p>
  </div>
</template>
