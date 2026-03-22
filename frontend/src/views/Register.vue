<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'student',
  company_name: '',
})
const error = ref('')
const auth = useAuthStore()
const router = useRouter()

async function submit() {
  error.value = ''
  try {
    await auth.register({ ...form.value })
    router.push('/internships')
  } catch (e) {
    const msg = e.response?.data?.message
    const errs = e.response?.data?.errors
    error.value = msg || (errs ? JSON.stringify(errs) : 'Inscription impossible')
  }
}
</script>

<template>
  <div class="relative flex min-h-screen flex-col justify-center bg-gradient-to-b from-slate-50 to-brand-50/30 px-4 py-12">
    <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-br from-brand-500/10 via-transparent to-transparent" />
    <div class="relative mx-auto w-full max-w-lg">
      <div class="mb-8 text-center">
        <div
          class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-lg font-bold text-white shadow-lg"
        >
          S
        </div>
        <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">Créer un compte</h1>
        <p class="mt-2 text-sm text-slate-500">Rejoignez la plateforme en quelques étapes</p>
      </div>
      <form
        class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-soft sm:p-8"
        @submit.prevent="submit"
      >
        <div class="grid gap-4 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Nom complet</label>
            <input v-model="form.name" required class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" />
          </div>
          <div class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
            />
          </div>
          <div class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Profil</label>
            <select
              v-model="form.role"
              class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
            >
              <option value="student">Étudiant</option>
              <option value="company">Entreprise</option>
              <option value="supervisor">Superviseur</option>
            </select>
          </div>
          <div v-if="form.role === 'company'" class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Nom de l’entreprise</label>
            <input v-model="form.company_name" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Mot de passe</label>
            <input
              v-model="form.password"
              type="password"
              required
              class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
            />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Confirmation</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              required
              class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
            />
          </div>
        </div>
        <p v-if="error" class="mt-4 text-sm font-medium text-rose-600">{{ error }}</p>
        <button
          type="submit"
          class="mt-6 w-full rounded-xl bg-brand-600 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
        >
          S’inscrire
        </button>
      </form>
      <p class="mt-6 text-center text-sm text-slate-500">
        Déjà inscrit ?
        <router-link to="/login" class="font-semibold text-brand-600 hover:text-brand-700">Se connecter</router-link>
      </p>
    </div>
  </div>
</template>
