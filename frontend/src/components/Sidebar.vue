<script setup>
import { RouterLink, useRoute } from 'vue-router'
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth'

defineProps({
  mobileOpen: { type: Boolean, default: false },
})

defineEmits(['close'])

const auth = useAuthStore()
const route = useRoute()

const nav = computed(() => {
  const r = auth.role
  if (r === 'student') {
    return [
      {
        to: '/student/dashboard',
        label: 'Tableau de bord',
        icon: 'home',
      },
      { to: '/internships', label: 'Offres de stage', icon: 'search' },
      { to: '/student/applications', label: 'Mes candidatures', icon: 'doc' },
      { to: '/student/report', label: 'Rapport de stage', icon: 'upload' },
    ]
  }
  if (r === 'company') {
    return [
      { to: '/company/dashboard', label: 'Vue d’ensemble', icon: 'home' },
      { to: '/company/internships/new', label: 'Publier une offre', icon: 'plus' },
      { to: '/company/applications', label: 'Candidatures', icon: 'users' },
    ]
  }
  if (r === 'admin') {
    return [{ to: '/admin/dashboard', label: 'Analytics', icon: 'chart' }]
  }
  if (r === 'supervisor') {
    return [
      { to: '/supervisor/dashboard', label: 'Tableau de bord', icon: 'home' },
      { to: '/supervisor/evaluation', label: 'Évaluations', icon: 'star' },
    ]
  }
  return []
})

function isActive(path) {
  if (path === '/internships') return route.path.startsWith('/internships')
  return route.path === path || route.path.startsWith(path + '/')
}

const icons = {
  home: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
  search: 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
  doc: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
  upload: 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12',
  plus: 'M12 4v16m8-8H4',
  users: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
  chart: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
  star: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
}
</script>

<template>
  <aside
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-200/80 bg-white shadow-soft transition-transform duration-200 lg:static lg:z-0 lg:translate-x-0 lg:shadow-none"
    :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
  >
    <div class="flex h-16 items-center gap-2 border-b border-slate-100 px-5 lg:h-[4.5rem]">
      <div
        class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-sm font-bold text-white shadow-sm"
      >
        S
      </div>
      <div>
        <p class="text-sm font-bold tracking-tight text-slate-900">StageHub</p>
        <p class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Console</p>
      </div>
    </div>
    <nav class="flex-1 space-y-1 overflow-y-auto p-3">
      <RouterLink
        v-for="item in nav"
        :key="item.to"
        :to="item.to"
        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
        :class="
          isActive(item.to)
            ? 'bg-brand-50 text-brand-800 shadow-sm ring-1 ring-brand-500/20'
            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
        "
        @click="$emit('close')"
      >
        <span
          class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
          :class="isActive(item.to) ? 'bg-white text-brand-600 shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-white'"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons[item.icon]" />
          </svg>
        </span>
        {{ item.label }}
      </RouterLink>
    </nav>
    <div class="border-t border-slate-100 p-4">
      <RouterLink
        to="/internships"
        class="flex items-center gap-2 text-xs font-medium text-slate-500 hover:text-brand-600"
        @click="$emit('close')"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Voir le catalogue public
      </RouterLink>
    </div>
  </aside>
</template>
