<script setup>
import { RouterLink, useRoute } from 'vue-router'
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const route = useRoute()

const links = computed(() => {
  const r = auth.role
  if (r === 'student') {
    return [
      { to: '/student/dashboard', label: 'Tableau de bord' },
      { to: '/internships', label: 'Rechercher un stage' },
      { to: '/student/applications', label: 'Mes candidatures' },
      { to: '/student/report', label: 'Rapport de stage' },
    ]
  }
  if (r === 'company') {
    return [
      { to: '/company/dashboard', label: 'Tableau de bord' },
      { to: '/company/internships/new', label: 'Publier une offre' },
      { to: '/company/applications', label: 'Candidatures' },
    ]
  }
  if (r === 'admin') {
    return [{ to: '/admin/dashboard', label: 'Administration' }]
  }
  if (r === 'supervisor') {
    return [
      { to: '/supervisor/dashboard', label: 'Tableau de bord' },
      { to: '/supervisor/evaluation', label: 'Évaluations' },
    ]
  }
  return []
})
</script>

<template>
  <aside
    class="hidden w-56 shrink-0 border-r border-slate-200 bg-white p-4 lg:block"
    v-if="links.length"
  >
    <nav class="flex flex-col gap-1 text-sm">
      <RouterLink
        v-for="l in links"
        :key="l.to"
        :to="l.to"
        class="rounded-lg px-3 py-2 font-medium text-slate-600 hover:bg-slate-100"
        :class="{ 'bg-brand-50 text-brand-700': route.path.startsWith(l.to) }"
      >
        {{ l.label }}
      </RouterLink>
    </nav>
  </aside>
</template>
