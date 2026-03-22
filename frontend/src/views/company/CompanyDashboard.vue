<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import DashboardStats from '../../components/DashboardStats.vue'

const profile = ref(null)
const internships = ref([])

onMounted(async () => {
  const [p, i] = await Promise.all([
    client.get('/company/profile'),
    client.get('/company/internships').catch(() => ({ data: { data: [] } })),
  ])
  profile.value = p.data.data ?? p.data
  internships.value = i.data.data || i.data || []
})
</script>

<template>
  <div>
    <h1 class="text-xl font-bold">Entreprise</h1>
    <p v-if="profile" class="mt-2 text-slate-600">{{ profile.name }} — {{ profile.approval_status }}</p>
    <DashboardStats
      class="mt-6"
      :stats="[
        { label: 'Offres publiées', value: internships.filter((x) => x.status === 'published').length },
        { label: 'Total offres', value: internships.length },
      ]"
    />
  </div>
</template>
