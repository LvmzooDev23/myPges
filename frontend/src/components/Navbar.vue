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
  <header class="border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 lg:px-8">
      <RouterLink to="/" class="text-lg font-semibold text-brand-600"> StageHub </RouterLink>
      <nav class="flex items-center gap-4 text-sm font-medium text-slate-600">
        <RouterLink to="/internships" class="hover:text-brand-600"> Offres </RouterLink>
        <template v-if="auth.isAuthenticated">
          <NotificationDropdown />
          <RouterLink
            v-if="auth.role === 'student'"
            to="/student/dashboard"
            class="hover:text-brand-600"
          >
            Espace étudiant
          </RouterLink>
          <RouterLink
            v-if="auth.role === 'company'"
            to="/company/dashboard"
            class="hover:text-brand-600"
          >
            Entreprise
          </RouterLink>
          <RouterLink
            v-if="auth.role === 'admin'"
            to="/admin/dashboard"
            class="hover:text-brand-600"
          >
            Admin
          </RouterLink>
          <RouterLink
            v-if="auth.role === 'supervisor'"
            to="/supervisor/dashboard"
            class="hover:text-brand-600"
          >
            Superviseur
          </RouterLink>
          <button
            type="button"
            class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50"
            @click="doLogout"
          >
            Déconnexion
          </button>
        </template>
        <template v-else>
          <RouterLink to="/login" class="hover:text-brand-600"> Connexion </RouterLink>
          <RouterLink
            to="/register"
            class="rounded-lg bg-brand-600 px-3 py-1.5 text-white hover:bg-brand-500"
          >
            Inscription
          </RouterLink>
        </template>
      </nav>
    </div>
  </header>
</template>
