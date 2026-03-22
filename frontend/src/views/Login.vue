<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const email = ref('')
const password = ref('')
const error = ref('')
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

async function submit() {
  error.value = ''
  try {
    await auth.login({ email: email.value, password: password.value })
    router.push(route.query.redirect || '/internships')
  } catch (e) {
    error.value = e.response?.data?.message || 'Connexion impossible'
  }
}
</script>

<template>
  <div class="relative flex min-h-screen flex-col justify-center bg-gradient-to-b from-slate-50 to-brand-50/30 px-4 py-12">
    <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-br from-brand-500/10 via-transparent to-transparent" />
    <div class="relative mx-auto w-full max-w-md">
      <div class="mb-8 text-center">
        <div
          class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-lg font-bold text-white shadow-lg"
        >
          S
        </div>
        <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">Connexion</h1>
        <p class="mt-2 text-sm text-slate-500">Accédez à votre espace StageHub</p>
      </div>
      <form
        class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-soft sm:p-8"
        @submit.prevent="submit"
      >
        <div class="space-y-4">
          <div>
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input
              v-model="email"
              type="email"
              required
              autocomplete="email"
              class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
            />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Mot de passe</label>
            <input
              v-model="password"
              type="password"
              required
              autocomplete="current-password"
              class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
            />
          </div>
        </div>
        <p v-if="error" class="mt-4 text-sm font-medium text-rose-600">{{ error }}</p>
        <button
          type="submit"
          class="mt-6 w-full rounded-xl bg-brand-600 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
        >
          Se connecter
        </button>
      </form>
      <p class="mt-6 text-center text-sm text-slate-500">
        Pas encore de compte ?
        <router-link to="/register" class="font-semibold text-brand-600 hover:text-brand-700">Créer un compte</router-link>
      </p>
    </div>
  </div>
</template>
