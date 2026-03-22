<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import DashboardStats from '../../components/DashboardStats.vue'
import UiCard from '../../components/ui/UiCard.vue'

const profile = ref(null)

onMounted(async () => {
  const { data } = await client.get('/student/profile')
  profile.value = data.data ?? data
})
</script>

<template>
  <div class="space-y-8">
    <div>
      <p class="text-sm font-medium text-brand-600">Bienvenue</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Tableau de bord étudiant</h1>
      <p v-if="profile" class="mt-2 text-slate-600">
        Bonjour <span class="font-semibold text-slate-900">{{ profile.user?.name }}</span> — gérez votre profil et vos candidatures.
      </p>
    </div>

    <DashboardStats
      v-if="profile"
      :stats="[
        { label: 'N° étudiant', value: profile.student_number || '—' },
        { label: 'CV', value: profile.cv_path ? 'Déposé' : 'À compléter' },
      ]"
    />

    <UiCard v-if="profile" title="Prochaines étapes" subtitle="Pour maximiser vos chances">
      <ul class="space-y-3 text-sm text-slate-600">
        <li class="flex gap-3">
          <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">1</span>
          Complétez votre profil et déposez votre CV.
        </li>
        <li class="flex gap-3">
          <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">2</span>
          Parcourez le catalogue et postulez aux offres qui vous correspondent.
        </li>
        <li class="flex gap-3">
          <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">3</span>
          Suivez vos candidatures depuis le menu latéral.
        </li>
      </ul>
    </UiCard>
  </div>
</template>
