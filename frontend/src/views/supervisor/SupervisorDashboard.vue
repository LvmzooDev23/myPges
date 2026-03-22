<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'
import UiBadge from '../../components/ui/UiBadge.vue'

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
  <div class="space-y-8">
    <div>
      <p class="text-sm font-medium text-brand-600">Encadrement</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Supervision des stages</h1>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
      <UiCard title="Étudiants assignés" :subtitle="`${students.length} profil(s)`">
        <ul class="divide-y divide-slate-100">
          <li v-for="st in students" :key="st.id" class="flex items-center justify-between py-3 first:pt-0">
            <div>
              <p class="font-medium text-slate-900">{{ st.user?.name }}</p>
              <p class="text-sm text-slate-500">{{ st.student_number || '—' }}</p>
            </div>
            <UiBadge variant="brand">Assigné</UiBadge>
          </li>
          <li v-if="!students.length" class="py-6 text-center text-sm text-slate-500">Aucun étudiant pour le moment.</li>
        </ul>
      </UiCard>

      <UiCard title="Rapports en attente" subtitle="À valider">
        <ul class="space-y-3">
          <li
            v-for="r in pending"
            :key="r.id"
            class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3"
          >
            <span class="text-sm font-medium text-slate-800">{{ r.title || 'Rapport' }}</span>
            <UiBadge variant="warning">À traiter</UiBadge>
          </li>
          <li v-if="!pending.length" class="py-6 text-center text-sm text-slate-500">Aucun rapport en attente.</li>
        </ul>
      </UiCard>
    </div>
  </div>
</template>
