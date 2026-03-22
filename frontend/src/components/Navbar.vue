<script setup>
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import NotificationDropdown from './NotificationDropdown.vue'

const auth = useAuthStore()
const router = useRouter()

async function doLogout() {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/80 backdrop-blur-lg">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
      <RouterLink to="/internships" class="flex items-center gap-2.5">
        <span
          class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-sm font-bold text-white shadow-sm"
        >
          S
        </span>
        <span class="text-lg font-bold tracking-tight text-slate-900">StageHub</span>
      </RouterLink>
      <nav class="flex items-center gap-1 sm:gap-2">
        <RouterLink
          to="/internships"
          class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
        >
          Offres
        </RouterLink>
        <template v-if="auth.isAuthenticated">
          <NotificationDropdown />
          <RouterLink
            v-if="auth.role === 'student'"
            to="/student/dashboard"
            class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 sm:inline-flex"
          >
            Espace étudiant
          </RouterLink>
          <RouterLink
            v-if="auth.role === 'company'"
            to="/company/dashboard"
            class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 sm:inline-flex"
          >
            Entreprise
          </RouterLink>
          <RouterLink
            v-if="auth.role === 'admin'"
            to="/admin/dashboard"
            class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 sm:inline-flex"
          >
            Admin
          </RouterLink>
          <RouterLink
            v-if="auth.role === 'supervisor'"
            to="/supervisor/dashboard"
            class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 sm:inline-flex"
          >
            Superviseur
          </RouterLink>
          <button
            type="button"
            class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
            @click="doLogout"
          >
            Déconnexion
          </button>
        </template>
        <template v-else>
          <RouterLink
            to="/login"
            class="rounded-xl px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100"
          >
            Connexion
          </RouterLink>
          <RouterLink
            to="/register"
            class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
          >
            Inscription
          </RouterLink>
        </template>
      </nav>
    </div>
  </header>
</template>
