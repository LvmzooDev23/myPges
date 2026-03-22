<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import NotificationDropdown from '../NotificationDropdown.vue'

const emit = defineEmits(['open-sidebar'])

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()

const pageTitle = computed(() => {
  const m = route.matched
    .map((r) => r.meta?.title)
    .filter(Boolean)
  return m.length ? m[m.length - 1] : 'Tableau de bord'
})

const roleLabel = computed(() => {
  const map = { admin: 'Administrateur', student: 'Étudiant', company: 'Entreprise', supervisor: 'Superviseur' }
  return map[auth.role] || ''
})

async function logout() {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <header
    class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between gap-4 border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-md sm:px-6"
  >
    <div class="flex min-w-0 items-center gap-3">
      <button
        type="button"
        class="inline-flex rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
        aria-label="Ouvrir le menu"
        @click="emit('open-sidebar')"
      >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <div class="min-w-0">
        <p class="truncate text-base font-semibold text-slate-900">{{ pageTitle }}</p>
        <p class="truncate text-xs text-slate-500">{{ roleLabel }} · {{ auth.user?.name }}</p>
      </div>
    </div>
    <div class="flex items-center gap-2 sm:gap-3">
      <div class="hidden max-w-xs sm:block">
        <div class="pointer-events-none flex items-center rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 text-sm text-slate-400">
          <svg class="mr-2 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            />
          </svg>
          Recherche rapide…
        </div>
      </div>
      <NotificationDropdown />
      <button
        type="button"
        class="hidden rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 sm:inline-flex"
        @click="logout"
      >
        Déconnexion
      </button>
    </div>
  </header>
</template>
