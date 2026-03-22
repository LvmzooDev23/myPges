<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import DashboardStats from '../../components/DashboardStats.vue'
import UiCard from '../../components/ui/UiCard.vue'
import UiBadge from '../../components/ui/UiBadge.vue'

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

const approvalVariant = (s) => {
  if (s === 'approved') return 'success'
  if (s === 'rejected') return 'danger'
  return 'warning'
}
</script>

<template>
  <div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-brand-600">Entreprise</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Vue d’ensemble</h1>
        <p v-if="profile" class="mt-2 flex flex-wrap items-center gap-2 text-slate-600">
          <span class="font-semibold text-slate-900">{{ profile.name }}</span>
          <UiBadge :variant="approvalVariant(profile.approval_status)">
            {{ profile.approval_status === 'approved' ? 'Approuvée' : profile.approval_status === 'pending' ? 'En attente' : 'Refusée' }}
          </UiBadge>
        </p>
      </div>
    </div>

    <DashboardStats
      v-if="profile"
      :stats="[
        { label: 'Offres publiées', value: internships.filter((x) => x.status === 'published').length },
        { label: 'Total offres', value: internships.length },
        { label: 'Brouillons', value: internships.filter((x) => x.status === 'draft').length },
      ]"
    />

    <UiCard v-if="profile?.approval_status !== 'approved'" title="Validation" subtitle="Votre compte doit être approuvé par un administrateur pour publier des offres.">
      <p class="text-sm text-amber-800">
        Statut actuel :
        <strong>{{ profile?.approval_status }}</strong>
      </p>
    </UiCard>
  </div>
</template>
