<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'

const students = ref([])
const pending = ref([])

onMounted(async () => {
  const [s, p] = await Promise.all([
    client.get('/supervisor/students'),
    client.get('/supervisor/reports/pending'),
  ])
  students.value = s.data.data || s.data
  pending.value = p.data.data || p.data
})
</script>

<template>
  <div>
    <h1 class="text-xl font-bold">Superviseur</h1>
    <h2 class="mt-6 font-medium">Étudiants assignés</h2>
    <ul class="mt-2 space-y-2 text-sm">
      <li v-for="st in students" :key="st.id">{{ st.user?.name }} — {{ st.student_number }}</li>
    </ul>
    <h2 class="mt-8 font-medium">Rapports en attente</h2>
    <ul class="mt-2 space-y-2 text-sm">
      <li v-for="r in pending" :key="r.id">{{ r.title || 'Rapport' }} (#{{ r.id }})</li>
    </ul>
  </div>
</template>
