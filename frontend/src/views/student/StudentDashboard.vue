<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../../api/client'
import DashboardStats from '../../components/DashboardStats.vue'
import UiCard from '../../components/ui/UiCard.vue'
import ApplicationStatus from '../../components/ApplicationStatus.vue'

const profile = ref(null)
const applications = ref([])

onMounted(async () => {
  const [p, a] = await Promise.all([
    client.get('/student/profile'),
    client.get('/student/applications').catch(() => ({ data: { data: [] } })),
  ])
  profile.value = p.data.data ?? p.data
  applications.value = a.data.data || a.data || []
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

    <UiCard v-if="applications.length" title="Suivi des candidatures" subtitle="Statut à jour pour chaque envoi">
      <ul class="divide-y divide-slate-100">
        <li
          v-for="x in applications.slice(0, 5)"
          :key="x.id"
          class="flex flex-wrap items-center justify-between gap-3 py-3 first:pt-0 last:pb-0"
        >
          <div>
            <p class="font-medium text-slate-900">{{ x.internship?.title }}</p>
            <p class="text-sm text-slate-500">{{ x.internship?.company?.name }}</p>
          </div>
          <ApplicationStatus :status="x.status" />
        </li>
      </ul>
      <RouterLink
        v-if="applications.length > 5"
        to="/student/applications"
        class="mt-4 inline-block text-sm font-semibold text-brand-600 hover:text-brand-700"
      >
        Voir toutes les candidatures
      </RouterLink>
    </UiCard>

    <UiCard v-if="profile" title="Prochaines étapes" subtitle="Pour maximiser vos chances">
      <ul class="space-y-3 text-sm text-slate-600">
        <li class="flex gap-3">
          <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">1</span>
          <span>
            Complétez votre
            <RouterLink to="/student/profile" class="font-semibold text-brand-600 hover:text-brand-700">profil</RouterLink>
            et déposez votre CV.
          </span>
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
