<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'
import StudentDashboardStats from '../../components/StudentDashboardStats.vue'
import RecommendedInternships from '../../components/RecommendedInternships.vue'
import ApplicationTracking from '../../components/ApplicationTracking.vue'
import Notifications from '../../components/Notifications.vue'

const profile = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await client.get('/student/profile')
    profile.value = data.data ?? data
  } catch (error) {
    console.error('Failed to load profile:', error)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="space-y-8">
    <!-- Header with Notifications -->
    <div class="flex justify-between items-start">
      <div>
        <p class="text-sm font-medium text-brand-600">Bienvenue</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Tableau de bord étudiant</h1>
        <p v-if="profile" class="mt-2 text-slate-600">
          Bonjour <span class="font-semibold text-slate-900">{{ profile.user?.name }}</span> — gérez votre profil et vos candidatures.
        </p>
      </div>
      <Notifications />
    </div>

    <!-- Profile Completion Alert -->
    <UiCard 
      v-if="profile && profile.profile_completion < 80" 
      class="border-amber-200 bg-amber-50"
    >
      <div class="flex items-center space-x-3">
        <div class="flex-shrink-0">
          <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
            <span class="text-amber-600 font-semibold text-sm">!</span>
          </div>
        </div>
        <div class="flex-1">
          <h3 class="text-sm font-medium text-amber-800">Complétez votre profil</h3>
          <p class="text-sm text-amber-700 mt-1">
            Votre profil est complété à {{ profile.profile_completion }}%. 
            Ajoutez les informations manquantes pour augmenter vos chances de trouver un stage.
          </p>
          <div class="mt-2">
            <RouterLink 
              to="/student/profile" 
              class="text-sm font-medium text-amber-800 hover:text-amber-900"
            >
              Compléter mon profil →
            </RouterLink>
          </div>
        </div>
      </div>
    </UiCard>

    <!-- Dashboard Stats -->
    <StudentDashboardStats />

    <!-- Quick Actions -->
    <UiCard title="Actions rapides" subtitle="Accédez rapidement aux fonctionnalités principales">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <RouterLink 
          to="/student/profile"
          class="p-4 border border-slate-200 rounded-lg hover:border-brand-300 hover:shadow-md transition-all"
        >
          <div class="text-center">
            <div class="w-12 h-12 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <span class="text-2xl">👤</span>
            </div>
            <h3 class="font-medium text-slate-900">Mon Profil</h3>
            <p class="text-sm text-slate-600 mt-1">{{ profile?.profile_completion || 0 }}% complété</p>
          </div>
        </RouterLink>

        <RouterLink 
          to="/internships"
          class="p-4 border border-slate-200 rounded-lg hover:border-brand-300 hover:shadow-md transition-all"
        >
          <div class="text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <span class="text-2xl">🔍</span>
            </div>
            <h3 class="font-medium text-slate-900">Trouver un Stage</h3>
            <p class="text-sm text-slate-600 mt-1">Parcourir les offres</p>
          </div>
        </RouterLink>

        <RouterLink 
          to="/student/applications"
          class="p-4 border border-slate-200 rounded-lg hover:border-brand-300 hover:shadow-md transition-all"
        >
          <div class="text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <span class="text-2xl">📋</span>
            </div>
            <h3 class="font-medium text-slate-900">Mes Candidatures</h3>
            <p class="text-sm text-slate-600 mt-1">Suivi des postulations</p>
          </div>
        </RouterLink>
      </div>
    </UiCard>

    <!-- Recommended Internships -->
    <RecommendedInternships />

    <!-- Application Tracking -->
    <ApplicationTracking />

    <!-- Next Steps -->
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
          Parcourez les recommandations et postulez aux offres qui vous correspondent.
        </li>
        <li class="flex gap-3">
          <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">3</span>
          Suivez vos candidatures et restez attentif aux notifications.
        </li>
      </ul>
    </UiCard>
  </div>
</template>
